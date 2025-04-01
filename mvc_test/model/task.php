<?php
// /model/Task.php

class Task {
    public $id;
    public $name;

    // On permet d'instancier un objet sans passer d'arguments
    public function __construct($id = null, $name = null) {
        $this->id = $id;
        $this->name = $name;
    }

    // Méthode pour obtenir des tâches simples
    public static function getAll() {
        return [
            new Task(1, "Faire les courses"),
            new Task(2, "Aller à la gym"),
            new Task(3, "Écrire du code")
        ];
    }
}
?>
