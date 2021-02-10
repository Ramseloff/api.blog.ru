<?php
header('Content-type: json/application');
require 'connect.php';
require 'functions.php';

$method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['q'])) {
    $q = $_GET['q'];
    // echo ($q).PHP_EOL;
    $params = explode('/', $q);
    if (isset($params[0])) {
        $type = $params[0];
        if (isset($params[1])) {
            $recipe = $params[1];
            if (isset($params[2])) {
                $id = $params[2];
            }
        }
    }
}

if (isset ($q)) {
    if ($method === 'GET') {
        if (isset ($type) && $type === 'articles') {
            if (isset ($recipe) && isset ($id)) {
                if ($recipe === 'id') {
                    getArticle($connect, $id);        
				} elseif ($recipe === 'tag') {
                    getArticlesTag($connect, $id);     
				}
			} else {
                getArticles($connect);     
			}
	    }
    } elseif ($method === 'POST') {
        if($q === 'articles/create') {
                addArticle($connect, $_POST);
        } elseif ($q === 'articles/tag') {
            useArticlesTag($_POST);
		}   
    } elseif ($method === 'PUT') {
        if ($type === 'article') {
            if (isset($recipe) && $recipe === 'update') {
                if (isset($id)) {
                    $data = file_get_contents('php://input');
                    $data = json_decode($data, true);
                    updateArticle($connect, $id, $data);
                }
            }
        }
    } elseif ($method === 'DELETE') {
        if ($type === 'article') {
            if (isset($recipe) && $recipe === 'delete') {
                if (isset($id)) {
                    deleteArticle($connect, $id);
                }
            }
        }
    }
}
