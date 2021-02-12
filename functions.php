<?php

function getArticles ($connect) {

    $query = "SELECT * FROM articles ORDER BY article_id ASC ";
    $articles = pg_query($connect, $query);
    $articleslist = [];
    while($article = pg_fetch_assoc($articles)) {
        $articleslist[] = $article;
    }
    echo json_encode($articleslist);
}

function getArticle ($connect, $id) {

    $query = "SELECT * FROM articles WHERE article_id = '$id'";
    $article = pg_query($connect, $query);
    if (pg_num_rows($article) === 0) {
        http_response_code(404);
        $res = [
            "status" => false,
            "message" => "Article not found"
        ];
        echo json_encode($res);
    } else {
        $article = pg_fetch_assoc($article);
        echo json_encode($article);
    }
}

function getArticlesTag ($connect, $id) {
    //echo($id).PHP_EOL;
    $tag = explode('$', $id);
    $count = count($tag);
    //echo ($count).PHP_EOL;
    $tag_number = implode(',', $tag);
    //echo ($tag_number);
    $query = "SELECT a.article_id, a.article_title, a.article_content 
                FROM articles a INNER JOIN article2tag at ON a.article_id = at.article_id
                WHERE at.tag_id IN ($tag_number)
                GROUP BY a.article_id
                HAVING COUNT(*) = $count";
    $articles = pg_query($connect, $query);
    $articleslist = [];
    while($article = pg_fetch_assoc($articles)) {
        $articleslist[] = $article;    
	}
    echo json_encode($articleslist);
}

function addArticle ($connect, $data) {
    $title = $data['title'];
    $content = $data['content'];
    $query = "INSERT INTO articles (article_title, article_content) VALUES ('$title', '$content') RETURNING article_id";
    $last_id = pg_query($connect, $query);
    $last_id = pg_fetch_assoc($last_id);
    //echo($last_id['article_id']);

    http_response_code(201);

    $res = [
        "status" => true,
        "last_id" => $last_id['article_id']
    ];

    echo json_encode($res);
}

function updateArticle ($connect, $id, $data) {
    $title = $data['title'];
    $content = $data['content'];
    $query = "UPDATE articles SET article_title = '$title',  article_content = '$content' WHERE article_id = $id";
    pg_query($connect, $query);

    http_response_code(200);

    $res = [
        "status" => true,
        "message" => 'Article is updated'
    ];

    echo json_encode($res);
}

function deleteArticle ($connect, $id) {
    $query = "DELETE FROM articles WHERE article_id = $id";
    pg_query($connect, $query);

    http_response_code(200);

    $res = [
        "status" => true,
        "message" => 'Article is deleted'
    ];

    echo json_encode($res);
}

