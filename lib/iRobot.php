<?php

/**
 *  Class iRobot
 * a class for crawling web pages
 */
class iRobot
{

    // database connection details
    private $_dbhandle ;

    /**
     * @param $user
     * @param $pass
     * @param $database
     */
    // initialize class variables
    public function __construct($user, $pass, $database)
    {
        if (!$user || !$pass || !$database) {
            throw new BadMethodCallException();
        }

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=$database", $user, $pass);
            $this ->_dbhandle = $dbh;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

    }

    // crawl through a given link and get the links therein
    protected function _index($url)
    {
        $urlHandle = parse_url($url);
        $valonDoc = new DOMDocument();
        $valonXpath = new DOMXPath($valonDoc);

        switch ($urlHandle['host']) {

            case 'nairaland.com' :
                // get topic, poster, date and views
                $td = $valonXpath -> query("//td[@id]");
                foreach($td as $td) {

                    $data = array();
                    $data[ 'topic']= $valonXpath -> query("b[1]/a[1]", $td) -> item(0) -> textContent;
                    $data['url'] = $valonXpath -> query("b[1]/a[1]", $td) -> item(0) -> getAttribute('href');
                    $data['poster'] = $valonXpath -> query("span[1]/b[1]", $td) -> item(0) -> textContent;
                    $data['views'] = $valonXpath -> query("span[1]/b[3]", $td) -> item(0) -> textContent;
                    $data['date'] = $valonXpath -> query("span[1]/b[5]", $td) -> item(0) -> textContent;

                    // save to the database
                    $this->saveData('nairaland', $data);

                }
                break;
            case 'stackoverflow.com' :
                // find the questions
                $questions = $valonXpath -> query("//div[@class='question-summary']");
                foreach($questions as $question) {

                    $data = array();
                    $data[ 'topic'] = $valonXpath -> query("div[2]/h3[1]", $question) -> item(0) -> textContent;
                    $data['url'] = $valonXpath -> query("div[2]/h3[1]/a[1]", $question) -> item(0) -> getAttribute('href');
                    $data['poster'] = $valonXpath -> query("div[2]/div[3]/*/div[@class='user-details']/a[last()]", $question) -> item(0) -> textContent;
                    $data['votes'] = $valonXpath -> query("div[1]/div[2]/div[1]/*/span[1]", $question) -> item(0) -> textContent;
                    $data['date'] = $valonXpath -> query("div[2]/div[3]/*/div[@class='user-action-time']/span[1]", $question) -> item(0) -> textContent;

                    // save to the database
                    $this->saveData('stackoverflow', $data);
                }


        }



    }



    /**
     * save the indexed data to the database table
     * @param $table
     * @param $data
     */
    public function saveData ($table, $data) {

    }

    public function __destruct() {
        $this->_dbhandle = null;
    }

}
