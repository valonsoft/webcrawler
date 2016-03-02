<?php

/**
 *  Class iRobot
 * a class for crawling web pages
 */
class iRobot
{
    protected $_url;
    protected $_depth;
    protected $_found = array();
    // database connection details
    private $_user;
    private $_password;
    private $_database;

    // initialize class variables
    public function __construct($url, $depth = 5, $connFile)
    {
        if (!file ($connFile)) {
            throw new BadMethodCallException ('Invalid connection file');
            //exit();
        }
        require_once ($connFile);
        $this->_url = $url;
        $this->_depth = $depth;
        //$this-> user =

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
                $td = $xpath -> query("//td[@id]");
                foreach($td as $td) {
                    $topic = $xpath -> query("b[1]/a[1]", $td) -> item(0) -> textContent;
                    $topicURL = $xpath -> query("b[1]/a[1]", $td) -> item(0) -> getAttribute('href');
                    $poster = $xpath -> query("span[1]/b[1]", $td) -> item(0) -> textContent;
                    $views = $xpath -> query("span[1]/b[3]", $td) -> item(0) -> textContent;
                    $date = $xpath -> query("span[1]/b[5]", $td) -> item(0) -> textContent;

                    // write the crawled data to file
                    $line = $new_url.$topicURL . "\t" . $topic . "\t" . $poster . "\t" . $date . "\t" . $views . "\t" . PHP_EOL;
                    echo $line; file_put_contents('php/crawler_cache/data', $line, FILE_APPEND);

                }
                break;
            case 'stackoverflow.com' :
                // find the questions
                $questions = $xpath -> query("//div[@class='question-summary']");
                foreach($questions as $question) {
                    $topic = $xpath -> query("div[2]/h3[1]", $question) -> item(0) -> textContent;
                    $topicURL = $xpath -> query("div[2]/h3[1]/a[1]", $question) -> item(0) -> getAttribute('href');
                    $poster = $xpath -> query("div[2]/div[3]/*/div[@class='user-details']/a[last()]", $question) -> item(0) -> textContent;
                    $upvotes = $xpath -> query("div[1]/div[2]/div[1]/*/span[1]", $question) -> item(0) -> textContent;
                    $date = $xpath -> query("div[2]/div[3]/*/div[@class='user-action-time']/span[1]", $question) -> item(0) -> textContent;

                    // write crawled data to file
                    $line = $new_url.$topicURL . "\t" . $topic . "\t" . $poster . "\t" . $date . "\t" . "\t" . $upvotes . PHP_EOL;
                    echo $line; file_put_contents('php/crawler_cache/data', $line, FILE_APPEND);
                }

                // finish url setup
                $page = $i + 1;
                $new_url .= '/questions?page=' . $page . '&sort=votes';

        }

        // complete url setup
        $url = $new_url;

    }

    }

    /**
     * save the indexed data to the database table
     * @param $table
     * @param $data
     */
    public function saveData ($table, $data) {

    }








}
