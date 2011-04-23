<?php

/**
 * Basic interface for database classes
 * @author RadoRado (a.k.a Rado)
 */
interface IDatabase {

    // ----------------------------------------
    // CONNECTION RELATED
    // ----------------------------------------
    function openConnection($createTable = false);

    function closeConnection();

    function getConnection();

    // ----------------------------------------
    // QUERY RELATED
    // ----------------------------------------
    function query($sql);

    function setEncoding($encoding);

    // ----------------------------------------
    // RESULT RELATED
    // ----------------------------------------
    function fetchAssoc($result);

    function fetchArray($result);

    function fetchObject($result);

    function lastInsertedId();

    function affectedRows();

    function numRows($result);

    // ----------------------------------------
    // SECURITY RELATED
    // ----------------------------------------
    function lastError();

    function escape($value, $moreFunctions = null);
}

?>
