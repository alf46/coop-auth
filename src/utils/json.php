<?php function json($res, $data, $code = 200)
{
    if ($data != null) {
        $res->getBody()->write(json_encode($data));
    }

    $res = $res->withHeader('Content-Type', 'application/json');
    return $res->withStatus($code);
}