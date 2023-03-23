<?php

/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 01.07.15
 * Time: 10:59
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/article/article.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/system/system.php');

//error_reporting(0); // Turn off error reporting
//error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

extension_loaded('mbstring') or die('no mbstring');
function_exists('mb_ereg_replace') or die("skip mb_ereg_replace() is not available in this build");

use PHPHtmlParser\Dom;

//use App\Services\Connector; // Own connector et. curl

class CM
{
    public function __construct()
    {
        ini_set("memory_limit", "600M");
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes

        $this->welcome = new NAD();
        //$this->googleClient = new Google_Client_Multi();

        // Tags
        $this->allText = "";
        $this->rangeOffcompositionArticleText = 2;  // Максимальное Количество вставок текста за один раз

        // compositionText
        $this->rangeOnRandomDate = 3;              // Диапозон шанса на случайную дату
        $this->rangeOffRandomDate = 5;             // Диапозон из шанса на случайную дату

        // compositionText
        $this->compositionArticleTrue = [];
        $this->compositionArticleTrue["body"] = [];

        // getSlogan
        $this->attemptcompositionArticleSlogan = 8; // Попыток получения слогана

        // getText
        $this->attemptcompositionArticleText = 8;   // Попыток получения текста

        // getImage
        $this->attemptcompositionArticleImage = 5;  // Попыток получения изображения

        // parseTextUrl
        $this->minParagraphToPage = 10;             // Минимум параграфов на страницу
        //$minSentencesToPage = 2;
        $this->minWordToPage = 400;                 // Минимум слов на страницу
        $this->minSentencesToParagraph = 2;         // Минимум предложений на параграф
        //$trueSentencesToParagraph = 7;
        $this->maxSentencesToParagraph = 7;         // Максимум предложений на страницу
        $this->minWordToParagraph = 5;              // Минимум слов на параграф

        $this->rangeOnParagraphYellow = 3;          // Диапозон шанса на жёлтый параграф
        $this->rangeOffParagraphYellow = 5;         // Диапозон из шанса на жёлтый параграф

        $this->rangeOnWordRemove = 3;               // Диапозон шанса на удаление слова из параграфа
        $this->rangeOffWordRemove = 5;              // Диапозон из шанса на удаление слова из параграфа
        $this->rangeStartWordRemovePosition = 5;    // Начало удаление слова из параграфа

        // getGoogleTextUrl
        $this->rangeOnStartGooglePage = 7;          // Диапозон старта страниц гугл поиска
        $this->rangeOffStartGooglePage = 20;        // Диапозон конца страниц гугл поиска

        // parseUrlGoogleImageUrl
        $this->minImageHeight = 400;                // Минимум высота изображения
        $this->minImageWidth = 740;                 // Минимум ширина изображения
        $this->maxImageHeight = 900;                // Максимум высота изображения
        $this->maxImageWidth = 1200;                // Максимум ширина изображения

        $this->rangeOnImageYellow = 3;              // Диапозон шанса на жёлтое изображение
        $this->rangeOffImageYellow = 5;             // Диапозон из шанса на жёлтое изображение

        // getGoogleImageUrl
        $this->rangeNumGoogleImage = 10;             // Количество изображений на страницу гугл поиска
        $this->rangeStartGoogleImagePage = 20;       // Начало страниц гугл поиска

        // parseGoogleVideoUrl

        // getGoogleYouTubeUrl
        $this->rangeOffYouTubeResults = 50;         // Диапозон страниц YouTube поиска
        $this->paragraphText = [];

        // antiBedSlogan
        $this->reservedLastWordsSlogan = array('at', 'the', 'and', 'to', 'which', 'are', 'in', 'a', 'with', 'but', 'just', 'from', 'its', 'on', 'by', 'with', 'that', 'this', 'it');
        $this->stopWord = ["able", "10", "39", "a", "about", "above", "abroad", "according", "accordingly", "across", "actually", "ad", "adj", "ae", "af", "after", "afterwards", "ag", "again", "against", "ago", "ahead", "ai", "ain’t", "al", "all", "allow", "allows", "almost", "alone", "along", "alongside", "already", "also", "although", "always", "am", "amid", "amidst", "among", "amongst", "an", "and", "another", "any", "anybody", "anyhow", "anyone", "anything", "anyway", "anyways", "anywhere", "ao", "apart", "appear", "appreciate", "appropriate", "aq", "ar", "are", "aren", "aren't", "aren’t", "around", "arpa", "as", "aside", "ask", "asking", "associated", "at", "au", "available", "aw", "away", "awfully", "az", "b", "ba", "back", "backward", "backwards", "bb", "bd", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "begin", "beginning", "behind", "being", "believe", "below", "beside", "besides", "best", "better", "between", "beyond", "bf", "bg", "bh", "bi", "billion", "bj", "bm", "bn", "bo", "both", "br", "brief", "bs", "bt", "but", "buy", "bv", "bw", "by", "bz", "c", "c’mon", "ca", "came", "can", "can't", "can’t", "cannot", "cant", "caption", "cause", "causes", "cc", "cd", "certain", "certainly", "cf", "cg", "ch", "changes", "ci", "ck", "cl", "clearly", "click", "cm", "cn", "co", "co.", "com", "come", "comes", "concerning", "consequently", "consider", "considering", "contain", "containing", "contains", "copy", "corresponding", "could", "couldn", "couldn't", "couldn’t", "course", "cr", "cs", "cu", "currently", "cv", "cx", "cy", "cz", "d", "dare", "daren’t", "de", "definitely", "described", "despite", "did", "didn", "didn't", "didn’t", "different", "directly", "dj", "dk", "dm", "do", "does", "doesn", "doesn't", "doesn’t", "doing", "don", "don't", "don’t", "done", "down", "downwards", "during", "dz", "e", "each", "ec", "edu", "ee", "eg", "eh", "eight", "eighty", "either", "else", "elsewhere", "en", "end", "ending", "enough", "entirely", "er", "es", "especially", "et", "etc", "even", "ever", "evermore", "every", "everybody", "everyone", "everything", "everywhere", "ex", "exactly", "example", "except", "f", "fairly", "far", "farther", "few", "fewer", "fi", "fifth", "fifty", "find", "first", "five", "fj", "fk", "fm", "fo", "followed", "following", "follows", "for", "forever", "former", "formerly", "forth", "forty", "forward", "found", "four", "fr", "free", "from", "further", "furthermore", "fx", "g", "ga", "gb", "gd", "ge", "get", "gets", "getting", "gf", "gg", "gh", "gi", "given", "gives", "gl", "gm", "gmt", "gn", "go", "goes", "going", "gone", "got", "gotten", "gov", "gp", "gq", "gr", "greetings", "gs", "gt", "gu", "gw", "gy", "h", "had", "hadn’t", "half", "happens", "hardly", "has", "hasn", "hasn't", "hasn’t", "have", "haven", "haven't", "haven’t", "having", "he", "he'd", "he'll", "he's", "he’d", "he’ll", "he’s", "hello", "help", "hence", "her", "here", "here's", "here’s", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "hi", "him", "himself", "his", "hither", "hk", "hm", "hn", "home", "homepage", "hopefully", "how", "however", "hr", "ht", "htm", "html", "http", "hu", "hudred", "hundred", "i", "i.e.", "i'd", "i'll", "i'm", "i've", "i’d", "i’ll", "i’m", "i’ve", "id", "ie", "if", "ignored", "ii", "il", "im", "immediate", "in", "inc", "inc.", "indeed", "indicated", "indicates", "information", "inner", "inside", "instead", "int", "into", "inward", "io", "iq", "ir", "is", "isn", "isn't", "isn’t", "it", "it's", "it’d", "it’ll", "it’s", "its", "itself", "j", "je", "jm", "jo", "join", "jp", "just", "k", "ke", "keep", "keeps", "kept", "kg", "kh", "ki", "km", "kn", "know", "known", "knows", "kp", "kr", "kw", "ky", "kz", "l", "la", "last", "lately", "later", "latter", "latterly", "lb", "lc", "least", "length", "less", "lest", "let", "let's", "let’s", "li", "like", "liked", "likely", "likewise", "little", "lk", "ll", "look", "looking", "looks", "low", "lower", "lr", "ls", "lt", "ltd", "lu", "lv", "ly", "m", "ma", "made", "mainly", "make", "makes", "many", "may", "maybe", "mayn’t", "mc", "md", "me", "mean", "meantime", "meanwhile", "merely", "mg", "mh", "microsoft", "might", "mil", "million", "mine", "minus", "miss", "mk", "ml", "mm", "mn", "mo", "more", "moreover", "most", "mostly", "mp", "mq", "mr", "mrs", "ms", "msie", "mt", "mu", "much", "must", "mustn’t", "mv", "mw", "mx", "my", "myself", "mz", "n", "na", "name", "namely", "nc", "ndicate", "ne", "near", "nearly", "necessary", "needn’t", "needs", "neither", "net", "netscape", "never", "neverf", "neverless", "nevertheless", "new", "next", "nf", "ng", "ni", "nine", "ninety", "nl", "no", "nobody", "non", "none", "nonetheless", "noone", "nor", "normally", "not", "nothing", "notwithstanding", "novel", "now", "nowhere", "np", "nr", "nu", "NULL", "nz", "o", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "om", "on", "once", "one", "one's", "one’s", "ones", "only", "onto", "opposite", "or", "org", "other", "others", "otherwise", "ought", "oughtn’t", "our", "ours", "ourselves", "out", "outside", "over", "overall", "own", "p", "pa", "page", "particular", "particularly", "past", "pe", "per", "perhaps", "pf", "pg", "ph", "pk", "pl", "placed", "please", "plus", "pm", "pn", "possible", "pr", "presumably", "probably", "provided", "provides", "pt", "pw", "py", "q", "qa", "que", "quite", "r", "rather", "re", "really", "reasonably", "recent", "recently", "regarding", "regardless", "regards", "relatively", "reserved", "respectively", "right", "ring", "ro", "round", "ru", "rw", "s", "sa", "said", "same", "saw", "say", "saying", "says", "sb", "sc", "sd", "se", "second", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "sent", "serious", "seriously", "seven", "seventy", "several", "sg", "sh", "shall", "shan’t", "she", "she'd", "she'll", "she's", "she’d", "she’ll", "she’s", "should", "shouldn", "shouldn't", "shouldn’t", "si", "since", "site", "six", "sixty", "sj", "sk", "sl", "sm", "sn", "so", "some", "somebody", "someday", "somehow", "someone", "something", "sometime", "sometimes", "somewhat", "somewhere", "soon", "sorry", "specified", "specify", "specifying", "sr", "st", "still", "stop", "su", "sub", "such", "sure", "sv", "sy", "sz", "t", "take", "taken", "taking", "tc", "td", "tell", "ten", "tends", "test", "text", "tf", "tg", "th", "than", "thank", "thanks", "thanx", "that", "that'll", "that's", "that’ll", "that’s", "that’ve", "thats", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "there'll", "there's", "there’d", "there’ll", "there’re", "there’s", "there’ve", "thereafter", "thereby", "therefore", "therein", "theres", "thereupon", "these", "they", "they'd", "they'll", "they're", "they've", "they’d", "they’ll", "they’re", "they’ve", "thing", "things", "think", "third", "thirty", "this", "thorough", "thoroughly", "those", "though", "thousand", "three", "through", "throughout", "thru", "thus", "till", "tj", "tk", "tm", "tn", "to", "together", "too", "took", "toward", "towards", "tp", "tr", "tried", "tries", "trillion", "truly", "try", "trying", "tt", "tv", "tw", "twenty", "twice", "two", "twoW", "tz", "u", "ua", "ug", "uk", "um", "under", "underneath", "undoing", "unfortunately", "unless", "unlike", "unlikely", "until", "unto", "up", "upon", "upwards", "us", "use", "used", "useful", "uses", "using", "usually", "uy", "uz", "v", "va", "value", "various", "vc", "ve", "versus", "very", "vg", "vi", "via", "vn", "vs", "vs.", "vu", "w", "want", "wants", "was", "wasn", "wasn't", "wasn’t", "way", "we", "we'd", "we'll", "we're", "we've", "we’d", "we’ll", "we’re", "we’ve", "web", "webpage", "website", "welcome", "well", "went", "were", "weren", "weren't", "weren’t", "wf", "what", "what'll", "what's", "what’ll", "what’s", "what’ve", "whatever", "when", "whence", "whenever", "where", "where’s", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "whichever", "while", "whilst", "whither", "who", "who'd", "who'll", "who's", "who’d", "who’ll", "who’s", "whoever", "whole", "whom", "whomever", "whose", "why", "width", "will", "willing", "wish", "with", "within", "without", "won", "won't", "won’t", "wonder", "would", "wouldn", "wouldn't", "wouldn’t", "ws", "www", "x", "y", "ye", "yes", "yet", "you", "you'd", "you'll", "you're", "you've", "you’d", "you’ll", "you’re", "you’ve", "your", "yours", "yourself", "yourselves", "yt", "yu", "z", "za", "zero", "zm", "zr"];

        // antiFake
        $this->reservedWords = array('Copyright', '©', 'Facebook', 'javascript', 'wikipedia', 'wikimedia');
        $this->antiRepeatArray = array();
        $this->rangeWordRepeat = 4;                 // Диапозон страниц YouTube поиска

        // newSlogan
        $this->rangePageForNewSlogan = 5;           // Диапозон страниц для нового слогана
        $this->minWordsForNewSlogan = 7; //3        // Мин. слов в новом слогане
        $this->maxWordsForNewSlogan = 11; //5        // Макс. слов в новом слогане

        //echo "\n\r<br>---> randGoogleKey: ";
        switch (rand(1, 6)
//switch (rand(6, 6)
        ) {
            case 1:
// Google_Client_Multi ventozamail@gmail.com
                $this->googleClientMultiKeys = array(
                    'AIzaSyCsfU-aR2BveXo-VGXP9fDJHd4vc1k8WK0',
                    'AIzaSyDC2_s9s-pL3cBAWj_Zj7ryheJjjoXoYOE',
                    'AIzaSyC-GZUoW-nyDI0ApZC1Gkl5UHRDFdf1Xok',
                    'AIzaSyC994oqod5JIFAcwf2wrMLMUy9GDRCF5_E',
                    'AIzaSyDNdK1zAF2Ia5nGuTobjsZRDgbcqCU6SBY',
                    'AIzaSyAfC3mAE8_nvTWJg5Tx2RmN8o8R8wnq6SM'
                );
                $this->cx = '007620943826538184064:crqduorrwiy'; // ventozamail@gmail.com
                //echo "ventozamail@gmail.com";
                break;
            case 2:
// Google_Client_Multi kozmaprutkovru
                $this->googleClientMultiKeys = array(
                    'AIzaSyDV9ZNiVAmWByV14HUzE57fByL9884Rrjg',
                    'AIzaSyCzbEHl2olRZG0cL_FXjQs2pzu4LSe7R0g',
                    'AIzaSyDBgZvgQgzqamKlVIGq-1nJUdOlmxteD00',
                    'AIzaSyBi2eIvWp7CAJptjhFTUdbyv5aNLea9bDM',
                    'AIzaSyCa8VdBH2HSQLrsniiXnk_rGvvPnmj6J5M'
                );
                $this->cx = '012908197144626814495:26r52pnjp9g'; // kozmaprutkovru@gmail.com
                //echo "kozmaprutkovru@gmail.com";
                break;
            case 3:
// Google_Client_Multi gerald.gressett@gmail.com
                $this->googleClientMultiKeys = array(
                    'AIzaSyBEptdN9X4dQg60aHNQ3WVKavPgcDn5c3M',
                    'AIzaSyB58Bnz5mJbICeKPlnI7I6r1PksKK4azLs',
                    'AIzaSyBDnAMfyuV2fwl7RGDDTpVh_7djBuLOgKI',
                    'AIzaSyCNPyMQOSn0STr_RB5TlLaKWrU3niF49Tg',
                    'AIzaSyDDr40keIliU2uz5jWOCawblmjMdPzvAYA'
                );
                $this->cx = '016062767381851517504:f_29678oek0'; // gerald.gressett@gmail.com
                //echo "gerald.gressett@gmail.com";
                break;
            case 4:
                // Google_Client_Multi juliano.ferreira.auto@gmail.com
                $this->googleClientMultiKeys = array(
                    'AIzaSyAvFQcs1zBzdYiVjy0ExsIwUAO5SL5nQIU',
                    'AIzaSyDFYf_Zxf6CXjFynlwWaoWq7h8KDPWLyp4',
                    'AIzaSyA6nL9R0ztohCUrHtDvCxtFnBMV6okgPcw',
                    'AIzaSyDpmX326__8TqGtpVZeHLmNWDq7r3a7hgs',
                    'AIzaSyBZ0w5CPtzacR9vlUxMY0JVyTA0YmzYUJk'
                );
                $this->cx = '002017237872241468390:z5paedk6ypo'; // juliano.ferreira.auto@gmail.com
                //echo "juliano.ferreira.auto@gmail.com";
                break;
            case 5:
                // Google_Client_Multi henrique.persico.rossi@gmail.com
                $this->googleClientMultiKeys = array(
                    'AIzaSyDvsZOMdR3xVvM19L_wkM9akLDSjftfGRY',
                    'AIzaSyBfRbgIzDqGOkKkOyUtD0MbAxpK0cK00Iw',
                    'AIzaSyD493tULTZJwdkzqIlKTGeSaUr-ROyaw2k',
                    'AIzaSyC0Ab_1J7DyiaY-iWHZZ4eHuRjPhTgn1E8',
                    'AIzaSyDGsMrpSEB72BK4tV5I98Fai4HD1x8mRSc'
                );
                $this->cx = '017104830741851338123:1qrw_0o7m_k'; // henrique.persico.rossi@gmail.com
                //echo "henrique.persico.rossi@gmail.com";
                break;
            case 6:
                // Google_Client_Multi henrique.persico.rossi@gmail.com
                $this->googleClientMultiKeys = array(
                    'AIzaSyBqPgIkWkyfqgeYaTJXWi1t9KS7bBbS5W4',
                    'AIzaSyBK2iDxsWaTUy1XHmrCqfpe18UGhISdBoI',
                    'AIzaSyDfdeGd-h07i3nlsNxT3PJKruLthiUejjs',
                    'AIzaSyB89IKfYEGWujuo049EGX5KTIrS79r0DQw',
                    'AIzaSyCJuj3oO95xazXIZwVgb3sriZT10ug697Y'
                );
                $this->cx = 'AIzaSyBqPgIkWkyfqgeYaTJXWi1t9KS7bBbS5W4'; // luis.leleux.free@gmail.com
                $this->cx = '017791575660832192565:coygky43vba'; // luis.leleux.free@gmail.com
                //echo "luis.leleux.free@gmail.com";
                break;
            default:
                break;
        }
    }

    public function compositionArticle($compositionArticle)
    {
        echo "\n\r<br>---> compositionArticle: ";
        //print_r($compositionArticle);
        $this->compositionArticleTrue[$this->welcome->type] = $this->welcome->articleDraft;
        $this->compositionArticleTrue[$this->welcome->docId] = $this->welcome->trueRandom();
        if (rand(1, $this->rangeOffRandomDate) <= $this->rangeOnRandomDate) {
            $this->compositionArticleTrue["date"] = $this->welcome->randomDate(); // TODO: Может это убрать
            $this->compositionArticleTrue["time"] = $this->welcome->randomTime();
        }
        $this->compositionArticleTrue["title"] = $compositionArticle;
        $articleCover = $this->compositionImage($compositionArticle);
        $this->compositionArticleTrue["cover"] = $articleCover['img'];
        //$this->compositionArticleTrue[$this->welcome->userId] = "K1QtWhnkY3"; // old kozmaprutkovru@gmail.com
        $this->compositionArticleTrue[$this->welcome->userId] = "b6ddae836ee5"; // true kozmaprutkovru@gmail.com
        //$this->compositionArticleTrue["type"] = "article";
        $this->compositionArticleTrue["status"] = "draft";
        $this->compositionArticleTrue["category"] = "digest";
        array_push($this->compositionArticleTrue["body"], $this->compositionText($compositionArticle));
        array_push($this->compositionArticleTrue["body"], $this->compositionVideo($compositionArticle));
        array_push($this->compositionArticleTrue["body"], $this->compositionText($compositionArticle));
        array_push($this->compositionArticleTrue["body"], $this->compositionImage($compositionArticle));
        array_push($this->compositionArticleTrue["body"], $this->compositionText($compositionArticle));
        /*echo "<br>allText = <br>";
        print_r($this->allText);
        $tags = $this->extract_common_words($this->allText, $this->stopWord);
        echo "<br>tags = <br>";
        print_r($tags);*/
        //exit;
        $this->compositionArticleTrue[$this->welcome->tags] = $this->extract_common_words($this->allText, $this->stopWord);

        if (isset($this->compositionArticleTrue["body"]) and count($this->compositionArticleTrue["body"]) > 0) {
            $art = new Article();
            $art->ArticleNewDraft($this->compositionArticleTrue);
            /* Auto post to Twitter and Facebook
            $articleConfirm["article"] = $this->compositionArticleTrue["date"] . "/" . $this->compositionArticleTrue["article"];
            $articleConfirm["author"] = "b8ab3fecea1e";
            $art->articleConfirm($articleConfirm);*/
        } else {
            exit ("\n\r<br>---> compositionText: A attemp has been failed <br>");
        }
    }

    public function compositionShareVideo($compositionShareVideo)
    {
        echo "\n\r<br>---> compositionShare: ";
        //print_r($compositionArticle);
        $this->compositionArticleTrue[$this->welcome->type] = "shareVideoDraft";
        $this->compositionArticleTrue[$this->welcome->docId] = $this->welcome->trueRandom();
        /*if (rand(1, $this->rangeOffRandomDate) <= $this->rangeOnRandomDate) {
            $this->compositionArticleTrue["article"][$this->welcome->createdAt] = $this->welcome->randomDate(); // TODO: Может это убрать
            $this->compositionArticleTrue["article"]["time"] = $this->welcome->randomTime();
        }*/
        $ytId = $this->compositionVideo($compositionShareVideo);
        $this->compositionArticleTrue[$this->welcome->file] = $ytId["YTVideo"];
        $this->compositionArticleTrue[$this->welcome->ownerId] = "K1QtWhnkY3";
        $this->compositionArticleTrue[$this->welcome->subject] = $compositionShareVideo;
        $this->compositionArticleTrue["status"] = "draft";
        $this->compositionArticleTrue[$this->welcome->tags] = $this->extract_common_words($this->paragraphText, $this->stopWord, 3);
        echo "\n\r---> compositionShare: compositionArticleTrue \n";
        print_r($this->compositionArticleTrue);
        $art = new Article();
        $art->newShareVideo($this->compositionArticleTrue);

    }

    public function compositionImage($compositionImage)
    {
        echo "\n\r<br>---> compositionImage: ";
        print_r($compositionImage);
        $getImage = $this->getImage($compositionImage);
        if ($getImage and count($getImage) > 0) {
            echo "\n\r<br><--- compositionImage: <br>";
            print_r($getImage);
            return $getImage;
        } else {
            exit ("\n\r<br>---> compositionImage: All attemp has been failed <br>");
        }
    }

    public function getSlogan()
    {
        echo "\n\r<br>---> getSlogan: \n\r<br>";
        for ($i = 1; $i <= $this->attemptcompositionArticleSlogan; $i++) {
            echo "\n\r<br>--> getSlogan attamp = $i <br>";
            $getSlogan = $this->newSlogan();
            if ($getSlogan !== false and $this->antiRepeatSlogan($this->antiBadSlogan($this->getLastWord($getSlogan))) !== false) {
                return $getSlogan;
            }
            //if ($this->antiRepeatSlogan($getSlogan) !== false) return ;
        }
        return false;
    }

    public function getImage($getImage)
    {
        echo "\n\r<br>---> getImage: \n\r<br>";
        print_r($getImage);
        for ($i = 1; $i <= $this->attemptcompositionArticleImage; $i++) {
            echo "\n\r<br>--> getImage attamp = $i <br>";
            $compositionArticleImage = $this->parseGoogleImageUrl($this->getGoogleImageUrl($getImage));
            if ($compositionArticleImage !== false and $this->antiFake($compositionArticleImage) !== false) {
                echo "\n\r<br><--- getImage: <br>";
                print_r($getImage);
                return $compositionArticleImage;
            } else {
                sleep(1);
            }
        }
        return false;
    }

    public function parseGoogleImageUrl($parseUrlGoogleImageUrl)
    {
        echo "\n\r<br>---> parseGoogleImageUrl: ";
        $imageYellowKey = 0;                  // Начало массива изображения жёлтого
        $imageGreenKey = 0;                   // Начало массива изображения зелёного
        foreach ($parseUrlGoogleImageUrl['items'] as $key => $value) {
            if (($value['image']['height'] >= $this->minImageHeight
                    and $value['image']['height'] <= $this->maxImageHeight)
                and ($value['image']['width'] >= $this->minImageWidth
                    and $value['image']['width'] <= $this->maxImageWidth)
            ) {
                $imageGreen[$imageGreenKey] = array(
                    'img' => $value['link']/*,
                    //$value['link'],
                    'attr' => array(
                        'url' => $value['link'],
                        'imageGreen' => 'true',
                        'imageYellow' => 'false')*/
                );
                $imageGreenKey++;
            } else {
                $imageYellow[$imageYellowKey] = array(
                    'img' => $value['link']/*,
                    //$value['link'],
                    'attr' => array(
                        'url' => $value['link'],
                        'imageGreen' => 'false',
                        'imageYellow' => 'true')*/
                );
                $imageYellowKey++;
            }
        }

        if (isset($imageGreen) and count($imageGreen) > 0) {
            $imageUrl = $imageGreen[rand(0, count($imageGreen) - 1)];
            $resultCheckRemoteFile = $this->welcome->checkRemoteImage($imageUrl["img"]);
            echo "\n\r<br>---> parseGoogleImageUrl resultCheckRemoteFile: \n\r<br>";
            print_r($resultCheckRemoteFile);
            if ($resultCheckRemoteFile !== false) {
                echo "\n\r<br><--- parseGoogleImageUrl: \n\r<br>";
                print_r($imageUrl);
                return $imageUrl;
            } else {
                echo "\n\r<br><--- parseGoogleImageUrl: file image not exists\n\r";
                print_r($imageUrl);
                return false;
            }
        } elseif (isset($imageYellow) and count($imageYellow) > 0 and rand(1, $this->rangeOffImageYellow) <= $this->rangeOnImageYellow) {
            $imageUrl = $imageYellow[rand(0, count($imageYellow) - 1)];
            if ($this->welcome->checkRemoteImage($imageUrl["img"])) {
                echo "\n\r<br><--- parseGoogleImageUrl: \n\r";
                print_r($imageUrl);
                return $imageUrl;
            } else {
                echo "\n\r<br><--- parseGoogleImageUrl: file image not exists\n\r";
                print_r($imageUrl);
                return false;
            }
        } else {
            return false;
        }

    }

    public function getGoogleImageUrl($getGoogleImageUrl)
    {
        echo "\n\r<br>---> getGoogleImageUrl: ";
        print_r($getGoogleImageUrl);
        $client = new Google_Client_Multi();
        $client->setKeys($this->googleClientMultiKeys)->prepareMulti();
        $service = new Google_Service_Customsearch($client);
        $optParams = array(
            'imgType' => 'photo',
            'imgColorType' => 'color',
            'imgSize' => 'large',
            'searchType' => 'image',
            //'as_eq' => 'wiki',
            //'dateRestrict' => 'wiki',
            'num' => $this->rangeNumGoogleImage,
            'start' => $this->rangeStartGoogleImagePage,
            'safe' => 'medium',
            //'rights' => '(cc_publicdomain|cc_attribute|cc_sharealike)',
            //'filter' => '1',
            'filter' => '0',
            'cx' => $this->cx
        );
        echo "\n\r<br><--- getGoogleImageUrl ok!<br>";
        return $service->cse->listCse($getGoogleImageUrl, $optParams);
    }

    public function antiFake($antiFake)
    {
        $this->antiBadWord($antiFake);
        $this->antiRepeat($antiFake);
    }

    public function antiBadSlogan($antiBadSlogan)
    {
        echo "\n\r<br>---> antiBadSlogan: ";
        print_r($antiBadSlogan);
        foreach ($this->reservedLastWordsSlogan as $key => $value) {
            if (strpos(strtolower($antiBadSlogan), $value) !== false) {
                // TODO: error logs here
                echo "\n\r<br>---> antiBadSlogan exc. Last Words: $value";
                return false;
            }
        }
        return true;
    }

    public function antiRepeatSlogan($antiRepeatSlogan)
    {
        echo "\n\r<br>---> antiRepeatSlogan: ";
        print_r($antiRepeatSlogan);
        $bucketArticle = $this->welcome->autoConnectToBucket(["bucket" => "article"]);
        $query = CouchbaseViewQuery::from('article_by_date', 'date_article_title')->limit("1000")->range("9999/12/31", "0000/01/01", true)->order(CouchbaseViewQuery::ORDER_DESCENDING);
        try {
            //$res = $bucketArticle->query($query);
            $res = $this->welcome->SharePreParseData($bucketArticle->query($query));

        } catch (Exception $e) {
            exit ("Not found. " . $e->getMessage());
        }
        foreach ($res["rows"] as $value) {
            //echo "\n\r<br>---> antiRepeatSlogan ['value']['title']: ";
            //print_r($value['value']['title']);
            //echo "\n\r<br>";
            if (strpos(strtolower($antiRepeatSlogan), strtolower($value['value']['title'])) !== false) {
                // TODO: error logs here
                echo "\n\r<br>---> antiRepeatSlogan exc. Repiat Words: " . $value['value']['title'];
                echo "\n\r<br>";
                return false;
            }
        }
        return true;
    }

    public function antiBadWord($antiBadWord)
    {
        echo "\n\r<br>---> antiBadWord: ";
        print_r($antiBadWord);
        if (is_array($antiBadWord)) {
            $antiBadWord = implode(" ", $antiBadWord);
        }
        foreach ($this->reservedWords as $key => $value) {
            if (strpos($antiBadWord, $value) !== false) {
                // TODO: error logs here
                echo "<-- antiFake exc. Words: $value";
                return false;
            }
        }
        return true;
    }

    public function antiRepeat($antiRepeat)
    {
        echo "\n\r<br>---> antiRepeat: ";
        print_r($antiRepeat);
        if (!is_array($antiRepeat)) {
            $antiRepeat = explode(" ", $antiRepeat);
        }
        $slogan = implode(" ", array_splice($antiRepeat, 0, $this->rangeWordRepeat));
        foreach ($this->antiRepeatArray as $key => $value) {
            if ($value == $slogan) {
                // TODO: error logs here
                echo "<-- antiFake exc. Repeat: ";
                print_r($antiRepeat);
                return false;
            } else {
            }
        }
        return false;
    }

    public function compositionText($compositionText)
    {
        echo "\n\r<br>---> compositionText: ";
        print_r($compositionText);
        for ($i = 1; $i <= rand(1, $this->rangeOffcompositionArticleText); $i++) {
            echo "\n\r<br>rangeOffcompositionArticleText = $i<br>";
            $getText = $this->getText($compositionText);
            //echo "\n\r<br>---> compositionArticle getText: <br>";
            //print_r($getText);
            if (isset($getText) and count($getText["text"]) > 0) {
                array_push($this->compositionArticleTrue["body"], $getText);
            } else {
                exit ("\n\r<br>---> compositionText: A attemp has been failed <br>");
            }
        }
        echo "\n\r<br><--- compositionText: <br>";
        print_r($this->compositionArticleTrue);
        return $this->compositionArticleTrue;
    }

    public function getText($getText)
    {
        echo "\n\r<br>---> getText: ";
        print_r($getText);
        for ($i = 1; $i <= $this->attemptcompositionArticleText; $i++) {
            echo "\n\r<br>--> getText attamp = $i <br>";
            $compositionArticleText = $this->parseTextUrl($this->getGoogleTextUrl($getText));
            if ($compositionArticleText and $this->antiFake(implode($compositionArticleText)) !== false) {
                echo "\n\r<br><--- getText: <br>";
                print_r($compositionArticleText);
                $compositionArticleText["text"] = ucfirst(htmlspecialchars_decode($compositionArticleText["text"]));
                return $compositionArticleText;
                break;
            } else {
                echo "\n\r<br>---> getText: attemp $i\n\r<br>";
                //return false;
                sleep(1);
            }
        }
        return false;
    }

    public function nfigparseTextUrl($parseTextUrl)
    {
        echo "\n\r<br>---> parseTextUrl: ";
        print_r($parseTextUrl);
        $paragraphRedKey = 0;                 // Начало массива текста красного
        $paragraphYellowKey = 0;              // Начало массива текста жёлтого
        $paragraphGreenKey = 0;               // Начало массива текста зелёного
        $dom = new Dom;

        try {
            $dom->loadFromUrl($parseTextUrl);
            //$dom->loadFromUrl('http://google.com', [], new Connector);
            //$dom->loadFromUrl($parseTextUrl, [], new Connector);
//exit;
        } catch (Exception $e) {
            //echo "Exception loadFromUrl. " . $e;
            return false;
        }
        // Взять абзацы со страницы
        //echo "\n\r<br>---> parseTextUrl dom->getElementsByTag('p'): ";
        //print_r($dom->getElementsByTag('p'));
        //echo "\n\r<br><--- parseTextUrl dom->getElementsByTag('p'): ";
        if (count($dom->getElementsByTag('p')) >= $this->minParagraphToPage) {
            foreach ($dom->getElementsByTag('p') as $key => $value) {
                $paragraphCommon[$key] = $value->text;
                if (str_word_count($value->text) >= $this->minWordToParagraph) {
                    if ($this->countSentences($value->text) < $this->minSentencesToParagraph) {
                        // Если меньше минимума слов
                        $paragraphYellow[$paragraphYellowKey][$key] = $value->text;
                        $paragraphYellowKey++;
                    } elseif (($this->countSentences($value->text) >= $this->minSentencesToParagraph)
                        and
                        ($this->countSentences($value->text) <= $this->maxSentencesToParagraph)
                    ) {
                        // TODO: array_push($paragraphGreen);
                        // Если меньше или равно максимуму слов
                        $paragraphGreen[$paragraphGreenKey][$key] = $value->text;
                        $paragraphGreenKey++;
                    } elseif ($this->countSentences($value->text) > $this->maxSentencesToParagraph) {
                        // Если слишком много слов
                        $paragraphRed[$paragraphRedKey][$key] = $value->text;
                        $paragraphRedKey++;
                    }
                }
            }
            if (isset($paragraphCommon) and str_word_count(implode($paragraphCommon)) >= $this->minWordToPage
                //and count($paragraphCommon) >= $minParagraphToPage
            ) {
                if (rand(1, $this->rangeOffParagraphYellow) <= $this->rangeOnParagraphYellow and isset($paragraphGreen) and isset($paragraphYellow)) {
                    $paragraphMerge = implode($paragraphGreen[rand(0, count($paragraphGreen) - 1)] + $paragraphYellow[rand(0, count($paragraphYellow) - 1)]);
                    // Удалить случайное слово из абзаца
                    if (rand(1, $this->rangeOffWordRemove) <= $this->rangeOnWordRemove) $paragraphMerge = $this->removeRandomWord($paragraphMerge);
                    $textArray = array(
                        'text' => $paragraphMerge/*,
                        'text' => implode($paragraphMerge)/*,
                        //implode($paragraphMerge),
                        'attr' => array(
                            'url' => $parseUrl,
                            'paragraphYellow' => 'true',
                            'paragraphGreen' => 'true')*/
                    );
                    echo "\n\r<br><--- parseTextUrl: <br>";
                    print_r($textArray);
                    // Вернуть текст зелёного и жёлтого абзаца
                    //$this->allText .= implode($paragraphMerge);
                    $this->allText .= $paragraphMerge;
                    echo "\n\r<br><b> this->allText: </b><br>" . $this->allText . "<br>";
                    return $textArray;
                } elseif (isset($paragraphGreen)) {
                    $text = implode($paragraphGreen[rand(0, count($paragraphGreen) - 1)]);
                    // Удалить случайное слово из абзаца
                    if (rand(1, $this->rangeOffWordRemove) <= $this->rangeOnWordRemove) $text = $this->removeRandomWord($text);
                    $textArray = array(
                        'text' => $text/*,
                        //implode($paragraphGreen[rand(0, count($paragraphGreen) - 1)]),
                        'attr' => array(
                            'url' => $parseUrl,
                            'paragraphYellow' => 'false',
                            'paragraphGreen' => 'true')*/
                    );
                    echo "\n\r<br><--- parseTextUrl: <br>";
                    print_r($textArray);
                    // Вернуть текст только зелёного абзаца
                    $this->allText .= $text;
                    echo "\n\r<br><b> this->allText: </b><br>" . $this->allText . "<br>";
                    return $textArray;
                }
            } else {
                echo "\r\n<br>Word To Page <b>" . str_word_count(implode($paragraphCommon)) . "</b> < minWordToPage";
                return false;
            }
        } else {
            echo "\r\n<br>Paragraph To Page <b>" . count($dom->getElementsByTag('p')) . "</b> < minParagraphToPage";
            return false;
        }
        return false;
    }

    public function countSentences($countSentences)
    {
        $splitCountSentences = preg_split('/(?<=[.?!;:])\s+/', $countSentences, -1, PREG_SPLIT_NO_EMPTY);
        return count($splitCountSentences);
    }

    public function getGoogleTextUrl($getGoogleTextUrl)
    {
        echo "\n\r<br>---> getGoogleTextUrl: ";
        print_r($getGoogleTextUrl);
        $client = new Google_Client_Multi();
        $client->setKeys($this->googleClientMultiKeys)->prepareMulti();
        $service = new Google_Service_Customsearch($client);
        $optParams = array(
            'num' => '10',
            'start' => rand($this->rangeOnStartGooglePage, $this->rangeOffStartGooglePage),
            'cx' => $this->cx
        );
        try {
            $results = $service->cse->listCse($getGoogleTextUrl, $optParams);
        } catch (Exception $e) {
            echo "No text. " . $e;
            return false;
        }
        if (isset($results['items']) > 0) {
            echo "\n\r<br><--- getGoogleTextUrl: <br>";
            return $results['items'][rand(0, count($results['items']) - 1)]['link'];
        } else {
            return false;
        }
    }

    public function compositionVideo($compositionVideo)
    {
        echo "\n\r<br>---> compositionVideo: ";
        print_r($compositionVideo);
        $getVideo = $this->getVideo($compositionVideo);
        if (isset($getVideo) and count($getVideo) > 0) {
            echo "\n\r<br><--- compositionVideo: <br>";
            print_r($getVideo);
            return $getVideo;
        } else {
            exit ("\n\r<br>---> compositionVideo: All attemp has been failed <br>");
        }
    }

    public function getVideo($getVideo)
    {
        echo "\n\r<br>---> getVideo: ";
        print_r($getVideo);
        return $compositionArticleVideo = $this->parseGoogleVideoUrl($this->getGoogleYouTubeUrl($getVideo));
    }

    public function parseGoogleVideoUrl($parseGoogleVideoUrl)
    {
        echo "\n\r<br>---> parseGoogleVideoUrl ok! ";
        $videoGreenKey = 0;                   // Начало массива видео
        foreach ($parseGoogleVideoUrl['items'] as $searchResult) {
            switch ($searchResult['id']['kind']) {
                case 'youtube#video':
                    $videoGreen[$videoGreenKey] = array(
                        'YTVideo' => $searchResult['id']['videoId']/*,
                        'attr' => array(
                            'img' => $searchResult['snippet']['thumbnails']['high'],
                            'title' => $searchResult['snippet']['title'])*/
                    );
                    $videoGreenKey++;
                    break;
            }
        }
        if (isset($videoGreen) and count($videoGreen) > 0) {
            echo "\n\r<br><--- parseGoogleVideoUrl ok.<br>";
            return $videoGreen[rand(0, count($videoGreen) - 1)];
        } else {
            return false;
        }
    }

    public function getGoogleYouTubeUrl($getGoogleYouTubeUrl)
    {
        echo "\n\r<br>---> getGoogleYouTubeUrl: ";
        print_r($getGoogleYouTubeUrl);
        $client = new Google_Client_Multi();
        $client->setKeys($this->googleClientMultiKeys)->prepareMulti();
        $service = new Google_Service_YouTube($client);
        // https://developers.google.com/youtube/2.0/developers_guide_protocol_api_query_parameters#Custom_parameters
        $optParams = [
            'part' => 'snippet',
            'q' => $getGoogleYouTubeUrl,
            // not work  'duration' => 'short',
            'maxResults' => $this->rangeOffYouTubeResults,
            'type' => 'video'
        ];
        echo "\n\r<br><--- getGoogleYouTubeUrl ok.<br>";
        return $service->search->listSearch('id,snippet', $optParams);
    }

    public function newSlogan()
    {
        echo "\n\r<br>---> newSlogan: ";
        //$cluster = new CouchbaseCluster("http://192.168.0.181:8091", "Administrator", "Pilsner1", "default");
        //$myBucket = $cluster->openBucket('article');
        $bucket = $this->welcome->autoConnectToBucket(["bucket" => "article"]);

        echo "\n\r---> newSlogan bucket: \n\r";
        print_r($bucket);
        $query = CouchbaseViewQuery::from('article_by_date', 'date_article_title')->limit($this->rangePageForNewSlogan)->order(CouchbaseViewQuery::ORDER_DESCENDING);
        $res = $this->welcome->SharePreParseData($bucket->query($query));
        echo "\n\r---> newSlogan res: \n\r";
        print_r($res);
        $article = $bucket->get($res["rows"][rand(0, count($res["rows"]) - 1)]['id']);
        //$welcome = new NAD();
        $convert = $this->welcome->SharePreParseData($article->value);
        $paragraph = array();
        foreach ($convert["body"] as $value1) {
            foreach ($value1 as $value2) {
                switch (key($value1)) {
                    case "text":
                        array_push($paragraph, $value2);
                        break;
                    default:
                        break;
                }
            }
        }
        if (count($paragraph) > 0) {
            //echo "\n\r<br>---> article[body]paragraph: \n\r<br>";
            $paragraphText = $paragraph[rand(0, count($paragraph) - 1)];
            $this->paragraphText = $paragraphText;
            //print_r($paragraphText);
            $wordsForNewSlogan = rand($this->minWordsForNewSlogan, $this->maxWordsForNewSlogan);
            //echo "\n\r<br>---> wordsForNewSlogan: \n\r<br>";
            //print_r($wordsForNewSlogan);


            $words = explode(" ", $paragraphText);
            $newSlogan = implode(" ", array_splice($words, 0, $wordsForNewSlogan));
            echo "\n\r<br><--- newSlogan: $newSlogan: \n\r<br>";
            return ucfirst($newSlogan);
        } else {
            echo "No slogan";
            // TODO: error logs here
            ////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////
        }
        return false;
    }

    public function getLastWord($getLastWord)
    {
        echo "\n\r<br>---> getLastWord: ";
        $lastWordStart = strrpos($getLastWord, ' ') + 1;
        echo "\n\r<br>last word in slogan: " . substr($getLastWord, $lastWordStart);
        return substr($getLastWord, $lastWordStart);
    }

    public function removeRandomWord($removeRandomWord)
    {
        echo "\n\r<br>---> removeRandomWord: ";
        var_dump($removeRandomWord);
        //$words = str_word_count($removeRandomWord, 1);
        //$words = count($removeRandomWord);


        //$selection = array_slice($words, 0, 5);
        //$newText = array_slice($removeRandomWord, $removeWordPosition, $words - 1, true);
        //$newText = array_slice($removeRandomWord, $removeWordPosition, $removeWordPosition + 1, true);
        //$newText = array_slice($removeRandomWord, $removeWordPosition, $removeWordPosition - 1, true);
        //$newText = array_slice($removeRandomWord, 0, $removeWordPosition - 1, true);
        //$newText = array_slice($removeRandomWord, $removeWordPosition, $words - 1, true);
        //$newText = array_slice($removeRandomWord, 0, $removeWordPosition, true);
        //unset($removeRandomWord[$removeWordPosition]);

        //echo "\n\r<br>last word in slogan: " . substr($getLastWord, $lastWordStart);
        //return $newText;
        //return $removeRandomWord;


        //$to_remove = 2;
        //$text = "aa bb cc";

        $words = explode(' ', $removeRandomWord);
        echo "\n\r<br>---> removeRandomWord words: ";
        var_dump($words);


        $removeWordPosition = rand($this->rangeStartWordRemovePosition, count($words));

        echo "\n\r<br>---> removeRandomWord removeWordPosition: ";
        var_dump($removeWordPosition);
        echo "\n\r<br>";


        if (isset($words[$removeWordPosition])) unset($words[$removeWordPosition]);
        $text = implode(' ', $words);
        echo "\n\r<br>---> removeRandomWord text: ";
        var_dump($text);
        return $text;


    }

    public function extract_common_words($string, $stop_words, $max_count = 5)
    {
        // http://tools.seobook.com/general/keyword-density/stop_words.txt
        // http://stackoverflow.com/questions/3175390/most-used-words-in-text-with-php
        $string = preg_replace('/ss+/i', '', $string);
        $string = trim($string); // trim the string
        $string = preg_replace('/[^a-zA-Z -]/', '', $string); // only take alphabet characters, but keep the spaces and dashes too…
        $string = strtolower($string); // make it lowercase

        preg_match_all('/\b.*?\b/i', $string, $match_words);
        $match_words = $match_words[0];

        foreach ($match_words as $key => $item) {
            if ($item == '' || in_array(strtolower($item), $stop_words) || strlen($item) <= 3) {
                unset($match_words[$key]);
            }
        }

        $word_count = str_word_count(implode(" ", $match_words), 1);
        $frequency = array_count_values($word_count);
        //$frequency = str_word_count( implode(" ", $match_words) , 1);
        arsort($frequency);

        //arsort($word_count_arr);
        $keywords = array_slice($frequency, 0, $max_count);
        /*echo "\n\r<br>extract_common_words ---> frequency: <br>";
        print_r($frequency);
        echo "\n\r<br>extract_common_words ---> keywords: <br>";
        print_r($keywords);*/
        $newArray = array_keys($keywords);
        //return $keywords;
        return $newArray;
    }
}