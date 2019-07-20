<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    // Menampilkan pengunjung event
    $app->get("/guests/", function (Request $request, Response $response){
        $sql = "SELECT * FROM guest";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    // Menampilkan daftar event
    $app->get("/events/", function (Request $request, Response $response){
        $sql = "SELECT * FROM event";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    // Menambah user
    $app->post("/register/", function(Request $request, Response $response){
        $params = $request->getParsedBody();
        $sql = "INSERT INTO user (nama, email, password) VALUE (:nama, :email, :password)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":nama" => $params['nama'],
            ":email" => $params['email'],
            ":password" => password_hash($params['password'], PASSWORD_DEFAULT),
        ];

        if($stmt->execute($data))
                return $response->withJson(["status" => "success", "data" => $data], 200);
        return $response->withJson(["status" => "failed", "data" => "0"], 200);

    });

    // Menambah pengunjung suatu event
    $app->post("/guests/add", function (Request $request, Response $response){
        $params = $request->getParsedBody();
        $sql = "INSERT INTO guest (eventid, name, phone, email, address, notes) VALUE (:eventid, :name, :phone, :email, :address, :notes)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":eventid" => $params['eventid'],
            ":name" => $params['name'],
            ":phone" => $params['phone'],
            ":email" => $params['email'],
            ":address" => $params['address'],
            ":notes" => $params['notes'],
        ];

        if($stmt->execute($data))
            return $response->withJson(["status" => "success", "data" => "1"], 200);
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    // Menambah suatu event
    $app->post("/events/add", function (Request $request, Response $response){
        $params = $request->getParsedBody();
        $sql = "INSERT INTO event (title, location, date, host, description) VALUE (:title, :location, :date, :host, :description)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":title" => $params['title'],
            ":location" => $params['location'],
            ":date" => $params['date'],
            ":host" => $params['host'],
            ":description" => $params['description'],
        ];

        if($stmt->execute($data))
            return $response->withJson(["status" => "success", "data" => "1"], 200);
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    // Mengedit suatu event
    $app->put("/events/edit/{eventid}", function (Request $request, Response $response, $args){
        $eventid = $args["eventid"];
        $params = $request->getParsedBody();
        $sql = "UPDATE event SET title=:title, location=:location, date=:date, host=:host, description=:description WHERE eventid=:eventid";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":eventid" => $eventid,
            ":title" => $params['title'],
            ":location" => $params['location'],
            ":date" => $params['date'],
            ":host" => $params['host'],
            ":description" => $params['description'],
        ];

        if($stmt->execute($data))
                return $response->withJson(["status" => "success", "data" => $params['title']], 200);
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    // Menghapus suatu event
    $app->delete("/events/delete/{eventid}", function (Request $request, Response $response, $args){
        $eventid = $args['eventid'];
        $sql = "DELETE FROM event WHERE eventid=:eventid";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":eventid" => $eventid
        ];

        if($stmt->execute($data))
                return $response->withJson(["status" => "success", "data" => "1"], 200);
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
};
