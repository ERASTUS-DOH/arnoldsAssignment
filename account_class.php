<?php
//require_once 'include/db.inc.php';


class Account
{
    /*
    account_id
    account_username
    account_first_name
    account_last_name
    account_email
    account_password
    account_enabled
    */

    private $account_id;
    private $account_username;
    private $account_first_name;
    private $account_last_name;
    private $account_email;
    private $account_enabled;
//    private authenticated
    private $pdo;


    public function __construct()
    {
        $this->account_id = NULL;
        $this->account_username = NULL;
        $this->account_first_name = NULL;
        $this->account_last_name = NULL;
        $this->account_email = NULL;
        $this->account_enabled = FALSE;
        $this->pdo = $this->getDB();
    }

    private function getDB()
    {
        if ($this->pdo != null) {
            return $this->pdo;
        }

        return $this->initDB();
    }

    private function initDB()
    {
        $host = 'localhost';
        $user = 'root';
        $passwd = '';
        $schema = 'driverless';

        /* Connection string, or "data source name" */
        $dsn = 'mysql:host=' . $host . ';port=3306;dbname=' . $schema;

        try {
            return new \PDO($dsn, $user, $passwd, [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT => true
            ]);
        } catch (PDOException $e) {
            /* If there is an error an exception is thrown */
            echo 'Database connection failed.' . $e->getMessage();
            die();
        }
    }

    public function __destruct()
    {
    }

    /**
     * @throws Exception
     */
    public function addAccount(string $username, string $fname, string $lname, string $email, string $pwd): int
    {
        //validate the user details.

        $user_id = trim($username);
        $user_fname = trim($fname);
        $user_lname = trim($lname);
        $user_email = trim($email);
        $user_pwd = trim($pwd);

        if (!$this->userIsValid($user_id)) {
            throw new Exception('Invalid username or username invalid');
        }


        if (!$this->isEmailValid($user_email)) {
            throw new Exception('Invalid email');
        }

        if (!$this->isPasswordValid($user_pwd)) {
            throw new Exception('Invalid password');
        }

//        if (!is_null($this->getIdFromName($user_email, $user_id))) {
//            throw  new Exception('User name not available');
//        }

        //finally input the data.
        $query = 'INSERT INTO account(account_username, account_first_name, account_last_name, account_email, account_password) 
                    VALUES (:username, :fname, :lname, :email, :pwd)';

        //hash pwd.
        $hash = password_hash($user_pwd, PASSWORD_DEFAULT);

        //values array for PDO.
        $values = array(':username' => $user_id,
            ':fname' => $user_fname,
            ':lname' => $user_lname,
            ':email' => $user_email,
            ':pwd' => $hash);

        //exceute the query.
        try {
            $res = $this->pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

        return $this->pdo->lastInsertId();
    }

    /**
     * @throws Exception
     */
    public function userIsValid(string $user_id): bool
    {
        /* Example check: the length must be between 8 and 16 chars */
        $len = mb_strlen($user_id);
        if (($len < 8) || ($len > 16)) return false;

        $query = 'SELECT account_id FROM account WHERE (account_username = :name)';
        $values = array(':name' => $user_id);
        try {
            $res = $this->pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception($e->getMessage());
        }
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return empty($row);
    }

    public function isEmailValid(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $query = 'SELECT * FROM account WHERE (account_email = :email)';
        $values = array(':email' => $email);

        try {
            $res = $this->pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return empty($row);
    }

    public function isPasswordValid(string $password): bool
    {
//        $valid = TRUE;
//        $number = preg_match('@[0-9]@', $password);
//        $uppercase = preg_match('@[A-Z]@', $password);
//        $lowercase = preg_match('@[a-z]@', $password);
//        $specialChars = preg_match('@[^\w]@', $password);

//        if ( || !$number || !$uppercase || !$lowercase || !$specialChars) {
//            $valid = FALSE;
//            return $valid;
//        }

        return strlen($password) > 8;
    }

    public function getIdFromName(string $email = '', string $userId = '')
    {
        if (!is_null($email)) {
            if (!$this->isEmailValid($email)) {
                throw new Exception('Invalid email');
            }

            $query = 'SELECT * FROM account WHERE (account_email = :email)';
            $values = array(':email' => $email);

            try {
                $res = $this->pdo->prepare($query);
                $res->execute($values);
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                throw new Exception('Database query error');
            }

            $row = $res->fetch(PDO::FETCH_ASSOC);
            if (is_array($row)) {
                $id = intval($row['account_id'], 10);
            }

            return $id;
        }


        if (!is_null($userId)) {
            if (!$this->isUserIdValid($userId)) {
                throw new Exception('Invalid user name');
            }

            $query = 'SELECT * FROM myschema.account WHERE (account_username = :userId)';
            $values = array(':userId' => $userId);

            try {
                $res = $this->pdo->prepare($query);
                $res->execute($values);
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                throw new Exception('Database query error');
            }

            $row = $res->fetch(PDO::FETCH_ASSOC);
            if (is_array($row)) {
                $id = intval($row['account_id'], 10);
            }

            return $id;
        }

    }

    public function isIdValid(int $id): bool
    {
        /* Initialize the return variable */
        $valid = TRUE;

        /* Example check: the ID must be between 1 and 1000000 */

        if (($id < 1) || ($id > 1000000)) {
            $valid = FALSE;
        }

        /* You can add more checks here */

        return $valid;
    }

    public function editAccount(int $id, string $userId, string $fname, string $lname, string $email, string $passwd, bool $enabled)
    {
        /* Global $pdo object */
        global $pdo;

        /* Trim the strings to remove extra spaces */
        $account_userId = trim($userId);
        $account_fname = trim($fname);
        $account_lname = trim($lname);
        $account_email = trim($email);
        $account_password = trim($passwd);


        /* Check if the ID is valid */
        if (!$this->isIdValid($id)) {
            throw new Exception('Invalid account ID');
        }

        /* Check if the user name is valid. */
        if (!$this->isUserIdValid($account_userId)) {
            throw new Exception('Invalid user name');
        }

        /* Check if the password is valid. */
        if (!$this->isPasswordValid($account_password)) {
            throw new Exception('Invalid password');
        }

        /* Check if an account having the same name already exists (except for this one). */
        $idFromName = $this->getIdFromName($email, $userId);

        if (!is_null($idFromName) && ($idFromName != $id)) {
            throw new Exception('User name already used');
        }

        /* Finally, edit the account */

        /* Edit query template */
        $query = 'UPDATE myschema.account SET account_username = :userId, account_first_name = :fname, account_last_name = :lname, account_email = :email, account_password = :pwd, account_enabled = :enabled WHERE account_id = :id';

        /* Password hash */
        $hash = password_hash($account_password, PASSWORD_DEFAULT);

        /* Int value for the $enabled variable (0 = false, 1 = true) */
        $intEnabled = $enabled ? 1 : 0;

        /* Values array for PDO */
        $values = array(':userId' => $account_userId,
            ':fname' => $account_fname,
            ':lname' => $account_lname,
            ':email' => $account_email,
            ':pwd' => $hash,
            ':enabled' => $intEnabled,
            ':id' => $id);

        /* Execute the query */
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }
    }

    /* Delete an account (selected by its ID) */
    public function deleteAccount(int $id)
    {
        /* Global $pdo object */
        global $pdo;

        /* Check if the ID is valid */
        if (!$this->isIdValid($id)) {
            throw new Exception('Invalid account ID');
        }

        /* Query template */
        $query = 'DELETE FROM myschema.account WHERE account_id = :id';

        /* Values array for PDO */
        $values = array(':id' => $id);

        /* Execute the query */
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }

        /* Delete the Sessions related to the account */
        $query = 'DELETE FROM myschema.account_sessions WHERE (account_id = :id)';

        /* Values array for PDO */
        $values = array(':id' => $id);

        /* Execute the query */
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }
    }


    public function login(string $email = '', string $passwd): bool
    {
        /* Global $pdo object */


        /* Trim the strings to remove extra spaces */
//        $account_userId = trim($userId);
        $account_password = trim($passwd);
        $account_email = trim($email);

        /* Check if the user name is valid. If not, return FALSE meaning the authentication failed */
//        if (!$this->isEmailValid($email)) {
//            return FALSE;
//        }

        /* Check if the password is valid. If not, return FALSE meaning the authentication failed */
//        if (!$this->isPasswordValid($account_password)) {
//            return FALSE;
//        }

        /* Look for the account in the db. Note: the account must be enabled (account_enabled = 1) */
        $query = 'SELECT * FROM account WHERE (account_email= :email) ';
//        var_dump($query);

        /* Values array for PDO */
        $values = array(':email' => $account_email);

        /* Execute the query */
        try {
            $res = $this->pdo->prepare($query);
            $res->execute($values);

        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }

        $row = $res->fetch(PDO::FETCH_ASSOC);
        /* If there is a result, we must check if the password matches using password_verify() */
        if (is_array($row)) {
            if (password_verify($account_password, $row['account_password'])) {

                /* Authentication succeeded. Set the class properties (id and name) */
                $this->account_id = intval($row['account_id'], 10);
                $this->account_first_name = $row['account_first_name'];
                $this->account_last_name = $row['account_last_name'];
                $this->account_email = $row['account_email'];
                $this->account_username = $row['account_username'];
                $this->authenticated = TRUE;

                /* Register the current Sessions on the database */
                $this->registerLoginSession();

                /* Finally, Return TRUE */
                return TRUE;
            }

        }

        /* If we are here, it means the authentication failed: return FALSE */
        return FALSE;
    }

    /* Saves the current Session ID with the account ID */
    private function registerLoginSession()
    {
        /* Global $pdo object */


        /* Check that a Session has been started */
        if (session_status() == PHP_SESSION_ACTIVE){
            /* 	Use a REPLACE statement to:
                - insert a new row with the session id, if it doesn't exist, or...
                - update the row having the session id, if it does exist.
            */
            $query = 'INSERT INTO account_sessions (session_id, account_id, login_time) VALUES (:sid, :accountId, NOW())';
            $values = array(':sid' => session_id(), ':accountId' => $this->account_id);
            /* Execute the query */
            try {
                $res = $this->pdo->prepare($query);
                $res->execute($values);
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                throw new Exception('Database query error');
            }
        }
    }

    public function logout()
    {
        /* If there is no logged in user, do nothing */
//        if (is_null($this->account_id)) {
//            return;
//        }

        /* Reset the account-related properties */
        $this->account_id = NULL;
        $this->account_username = NULL;
        $this->account_first_name = NULL;
        $this->account_last_name = NULL;
        $this->account_email = NULL;
        $this->account_enabled = FALSE;

        /* If there is an open Session, remove it from the account_sessions table */
        if (session_status() == PHP_SESSION_ACTIVE) {
            /* Delete query */
            $query = 'DELETE FROM account_sessions WHERE (session_id = :sid)';
            /* Values array for PDO */
            $values = array(':sid' => session_id());
            /* Execute the query */
            try {
                $res = $this->pdo->prepare($query);
                $res->execute($values);

                return true;
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                throw new Exception('Database query error');
            }
        }
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    public function closeOtherSessions()
    {
        /* Global $pdo object */


        /* If there is no logged in user, do nothing */
        if (is_null($this->id)) {
            return;
        }

        /* Check that a Session has been started */
        if (session_status() == PHP_SESSION_ACTIVE) {
            /* Delete all account Sessions with session_id different from the current one */
            $query = 'DELETE FROM myschema.account_sessions WHERE (session_id != :sid) AND (account_id = :account_id)';

            /* Values array for PDO */
            $values = array(':sid' => session_id(), ':account_id' => $this->id);

            /* Execute the query */
            try {
                $res = $this->pdo->prepare($query);
                $res->execute($values);
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                throw new Exception('Database query error');
            }
        }
    }


}