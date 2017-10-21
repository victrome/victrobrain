<?php
        if(!isset($_SESSION)) session_start(); // Inicia a sessão
        session_destroy(); // Destrói a sessão limpando todos os valores salvos
        header("Location: ".SITE_URL."sys/home"); exit; // Redireciona o visitante
        ?>


