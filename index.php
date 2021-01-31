<?php
header('Content-type: json/application');
require 'connect.php';
require 'functions.php';

$method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['q'])) {
    $q = $_GET['q'];
    $params = explode('/', $q);
    if (isset($params[0])) {
        $type = $params[0];
        if (isset($params[1])) {
            $id = $params[1];
            if (isset($params[2])) {
                $recipe = $params[2];
            }
        }
    }
}
if ($method === 'GET'){
    if (isset($_GET['q'])) {
        if($type === 'articles') {
            if (isset ($type)) {
                if (isset($id)) {
                    getArticle($connect, $id);
                } else {
                    getArticles($connect);
                }
            }
        }
    }
} elseif ($method === 'POST') {
    if (isset($_GET['q'])) {
        if($q === 'articles/create') {
            if (isset ($type)) {
                addArticle($connect, $_POST);
            }
        }    
    }
} elseif ($method === 'PUT') {
    if (isset($_GET['q'])) {
        if ($type === 'article') {
            if (isset($id)) {
                if (isset($recipe)) {
                    if ($recipe === 'update') {
                        $data = file_get_contents('php://input');
                        $data = json_decode($data, true);
                        updateArticle($connect, $id, $data);
                    }
                }
            }
        }
    }
} elseif ($method === 'DELETE') {
    if (isset($_GET['q'])) {
        if ($type === 'article') {
            if (isset($id)) {
                if (isset($recipe)) {
                    if ($recipe === 'delete') {
                        deleteArticle($connect, $id);
                    }
                }
            }
        }
    }
}

