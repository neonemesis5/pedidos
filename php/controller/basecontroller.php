<?php

class BaseController {
    /**
     * Envía una respuesta JSON al cliente.
     *
     * @param mixed $data Datos a enviar como respuesta.
     * @param int $status Código de estado HTTP.
     */
    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Envía un mensaje de error al cliente.
     *
     * @param string $message Mensaje de error.
     * @param int $status Código de estado HTTP.
     */
    protected function errorResponse($message, $status = 400) {
        $this->jsonResponse(['error' => $message], $status);
    }
}
