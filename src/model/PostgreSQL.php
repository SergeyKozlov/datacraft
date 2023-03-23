<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 09.11.17
 * Time: 22:35
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');

class PostgreSQL
{
    public function __construct()
    {
        $this->pgConn = $this->pgConnect();
        //$this->pgConnOwn = $this->pgConnectOwn();
        $this->welcome = new NAD();
        $this->log = new log();

        $this->table_users = 'users';
        $this->table_users_prefer = 'users_prefer';
        $this->table_items = 'items';
        //$this->table_activity = 'activity3';
        $this->table_messages = 'messages';
        $this->table_signs = 'signs';
        $this->table_posts = 'posts';
        $this->table_pairs = 'pairs';
        $this->table_items_counts = 'items_counts';
        $this->table_users_items_views = 'users_items_views';
        $this->table_users_items_tags_views = 'users_items_tags_views';
        $this->table_users_items_tags_sets = 'users_items_tags_sets';
        $this->table_items_stars = 'items_stars';
        $this->table_relationships = 'relationships';
        $this->table_friendship = 'friendship';
        $this->table_tasks = 'tasks';
        $this->table_items_tags_array = 'items_tags_array';
        $this->table_access_items_friends = 'access_items_friends';
        $this->table_albums = 'albums';
        $this->table_albums_sets = 'albums_sets';
        $this->table_access_albums_friends = 'access_albums_friends';
        $this->table_items_likes = 'items_likes';
        $this->table_items_reposts = 'items_reposts';
        $this->table_external_links = 'external_links';
        $this->table_comments = 'comments';
        $this->table_items_tags = 'items_tags';
        $this->table_users_tags = 'users_tags';
        $this->table_items_trands = 'items_trands';
        $this->table_users_scores_tags = 'users_scores_tags';
        $this->table_essences = 'essences';
        $this->table_users_essences = 'users_essences';
        $this->table_users_ref_essences = 'users_ref_essences';
        $this->table_items_partners = 'items_partners';
        $this->table_facebook_users_deletion = 'facebook_users_deletion';
        $this->table_users_settings = 'users_settings';
        $this->table_lists_items = 'lists_items';
        $this->table_items_views = 'items_views';
        $this->table_el_trendmaker = 'el_trendmaker';
        //$this->table_access_items_followers = 'access_items_followers';
    }

    /**
     * @return string
     */
    public function getTableUsers(): string
    {
        return $this->table_users;
    }

    /**
     * @return string
     */
    public function getTableItems(): string
    {
        return $this->table_items;
    }

    /**
     * @return string
     */
    public function getTableActivity(): string
    {
        return $this->table_activity;
    }

    /**
     * @return string
     */
    public function getTableUsersPrefer(): string
    {
        return $this->table_users_prefer;
    }

    /**
     * @return string
     */
    public function getTableSigns(): string
    {
        return $this->table_signs;
    }

    /**
     * @return string
     */
    public function getTablePosts(): string
    {
        return $this->table_posts;
    }

    /**
     * @return string
     */
    public function getTablePairs(): string
    {
        return $this->table_pairs;
    }

    public function pgConnect()
    {
        //echo "\n\tclass PostgreSQL insight: ";

        $dotenv = Dotenv\Dotenv::createImmutable('/tmp/');
        $dotenv->load();

        $host = $_ENV['pg_host'];
        $port = $_ENV['pg_port'];
        $username = $_ENV['pg_username'];
        $password = $_ENV['pg_password'];
        $database = $_ENV['pg_database_pgvideme'];
        try {
            $conn = pg_pconnect("host=$host port=$port dbname=$database user=$username password=$password") or die("No base connect");
            return $conn;

        } catch (Exception $e) {
            echo 'No DB. ' . $e;
            return false;
            //echo "No file. ";
        }
    }

    public function pgOneDataByColumn($pgOneDataByColumn)
    {
        try {
            $result = pg_query($this->pgConn, "
              SELECT * 
              FROM " . $pgOneDataByColumn['table'] . " 
              WHERE " . $pgOneDataByColumn['find_column'] . " = '" . $pgOneDataByColumn['find_value'] . "'");
            //order by created_at desc");
            //echo "\npgOneDataByColumn \n";
            //print_r($result);
        } catch (Exception $e) {
            echo 'Pg. ' . $e;
            return false;
            //echo "No file. ";
        }
        //pg_close($this->pgConn);
        if ($result) {
            //return pg_fetch_row($result);
            return pg_fetch_assoc($result);
            //return pg_fetch_all($result);
            //return pg_fetch_result($result, 0);
        } else {
            return false;
        }

    }


    public function pgGetChartByItem1stDaysNOA($pgGetChartByItem1stDaysNOA)
    {
        // https://api.vide.me/v2/posts/shownew/
        try {
            $result = pg_query($this->pgConn, "
with intervals as (
  select generate_series(
    date_trunc(
      'day',
      '" . $pgGetChartByItem1stDaysNOA['start_date'] . "' at time zone 'UTC'
    ),
    date_trunc(
      'day',
      '" . $pgGetChartByItem1stDaysNOA['stop_date'] . "' at time zone 'UTC'
    ),
    interval '1 day'
  ) as days
)
select
  intervals.days as x,
  count(items_views.*) as y
from intervals
  left join items_views on intervals.days = date_trunc('day', created_at) and items_views.item_id = '" . $pgGetChartByItem1stDaysNOA['item_id'] . "'
  " . $pgGetChartByItem1stDaysNOA['where'] . "
group by intervals.days
order by intervals.days;");
        } catch (Exception $e) {
            echo 'Pg. ' . $e;
            return false;
            //echo "No file. ";
        }
        //pg_close($this->pgConn);
        if ($result) {
            return pg_fetch_all($result);
        } else {
            return false;
        }
    }

    public function pgGetChartPopStates($pgGetChartByItem1stDaysNOA)
    {
        // https://api.vide.me/v2/posts/shownew/
        try {
            $result = pg_query($this->pgConn, "
select
  items_views.state,
  --geoip2_state.state_id,
  geoip2_state.iso_code,
  geoip2_state.names,
  count(items_views.city) as count_state
from items_views 
  INNER JOIN geoip2_state on items_views.state = geoip2_state.state_id
  where items_views.item_id = '" . $pgGetChartByItem1stDaysNOA['item_id'] . "'
GROUP BY items_views.state, geoip2_state.iso_code, geoip2_state.names
order by count_state DESC
LIMIT '" . $pgGetChartByItem1stDaysNOA['limit'] . "';");
        } catch (Exception $e) {
            echo 'Pg. ' . $e;
            return false;
            //echo "No file. ";
        }
        //pg_close($this->pgConn);
        if ($result) {
            return pg_fetch_all($result);
        } else {
            return false;
        }
    }

    public function pgGetItemForTM_OLD($days)
    {
        //echo "\n\rpgGetItemForTM_OLD days\n\r";
        //print_r($days);
        try {
            $result = pg_query($this->pgConn, "
select items.item_id,
       items.title,
       items.content,
       items.created_at,
       et.period_now
from items
         left join el_trendmaker et on items.item_id = et.item_id
where et.period_now is null
  and et.latest_at is null
  and items.created_at < CURRENT_TIMESTAMP - INTERVAL '" . $days . " days'
  and items.type = 'video'
order by items.created_at
limit 1;");
        } catch (Exception $e) {
            echo 'Pg. ' . $e;
            return false;
            //echo "No file. ";
        }
        //pg_close($this->pgConn);
        if ($result) {
            return pg_fetch_all($result);
            //return pg_fetch_assoc($result);
            //return pg_fetch_row($result);
            //return pg_fetch_result($result, 0);
            //return false;
        } else {
            echo "\n\rpgGetItemForTM_OLD res empty\n\r";
            return false;
        }
    }

    public function pgAddData($table, $pgAddData)
    {
        //echo "\npgAddData pgAddData\n";
        //print_r($pgAddData);
        $trueItem = $this->pgPaddingItems($pgAddData);
        //echo "\npgAddData trueItem\n";
        //print_r($trueItem);
        return $this->pgInsertData($table, $trueItem);
    }

    public function pgUpdateData($table, $setColumn, $setVal, $whereColumn, $whereVal)
    {
        try {
            $result = pg_query($this->pgConn, "
                UPDATE " . $table . "
                SET " . $setColumn . " = '" . $setVal . "'
                WHERE " . $whereColumn ." = '" . $whereVal . "'");
        } catch (Exception $e) {
            echo 'Pg. ' . $e;
            return false;
            //echo "No file. ";

        }
        //pg_close($this->pgConn);
        return $result;
    }
    public function pgGeo2SetState($pgGeo2SetState)
    {
        $query =  "
INSERT INTO geoip2_state (state_id, iso_code, names)
VALUES
(
'" . $pgGeo2SetState['state_id'] . "',
'" . $pgGeo2SetState['iso_code'] . "',
'" . $pgGeo2SetState['names'] . "'
)
ON CONFLICT DO NOTHING;";
        try {
            //echo "\npgGeo2SetState query\n";
            //print_r($query);
            $res = pg_query($this->pgConn, $query);

        } catch (Exception $e) {
            echo 'Pg. ' . $e;
            return false;
            //echo "No file. ";
        }
    }

    public function pgInsertData($table, $pgInsertData)
    {
        //$pg = $this->pgConnect();
        //echo "\npgInsertData pgInsertData\n";
        //print_r($pgInsertData);
        try {
            $res = pg_insert($this->pgConn, $table, $pgInsertData);
        } catch (Exception $e) {
            /*$this->log->setEvent([
                "type" => "error",
                "message" => "pg",
                "val" => "cbFileAdd: ok",
                'event_id' => 'pg_ins_error',
                "file" => $_SERVER["PHP_SELF"],
                "class" => __CLASS__,
                "funct" => __FUNCTION__
            ]);*/
            echo 'Pg. ' . $e;
            return false;
            //echo "No file. ";
        }
        //pg_close($this->pgConn);
        return $res;
    }


    public function pgPaddingItems($pgPaddingItems)
    {
        //echo "\npgPaddingItems pgPaddingItems\n";
        //print_r($pgPaddingItems);
        $pgTrueItems = [];
        //  =========================================
        if (!empty($pgPaddingItems['updated_at']))
            $pgTrueItems['updated_at'] = $pgPaddingItems['updated_at'];

        // users ====================================
        if (!empty($pgPaddingItems['user_id']))
            $pgTrueItems['user_id'] = $pgPaddingItems['user_id'];

        if (!empty($pgPaddingItems['user_email']))
            $pgTrueItems['user_email'] = $pgPaddingItems['user_email'];

        if (!empty($pgPaddingItems['user_display_name']))
            $pgTrueItems['user_display_name'] = $pgPaddingItems['user_display_name'];

        if (!empty($pgPaddingItems['user_first_name']))
            $pgTrueItems['user_first_name'] = $pgPaddingItems['user_first_name'];

        if (!empty($pgPaddingItems['user_last_name']))
            $pgTrueItems['user_last_name'] = $pgPaddingItems['user_last_name'];

        if (!empty($pgPaddingItems['user_link']))
            $pgTrueItems['user_link'] = $pgPaddingItems['user_link'];

        if (!empty($pgPaddingItems['user_gender']))
            $pgTrueItems['user_gender'] = $pgPaddingItems['user_gender'];

        if (!empty($pgPaddingItems['user_birthday']))
            $pgTrueItems['user_birthday'] = $pgPaddingItems['user_birthday'];

        if (!empty($pgPaddingItems['user_locale']))
            $pgTrueItems['user_locale'] = $pgPaddingItems['user_locale'];

        if (!empty($pgPaddingItems['user_picture']))
            $pgTrueItems['user_picture'] = $pgPaddingItems['user_picture'];

        if (!empty($pgPaddingItems['user_cover']))
            $pgTrueItems['user_cover'] = $pgPaddingItems['user_cover'];

        if (!empty($pgPaddingItems['user_cover_top']))
            $pgTrueItems['user_cover_top'] = $pgPaddingItems['user_cover_top'];

        if (!empty($pgPaddingItems['spring']))
            $pgTrueItems['spring'] = $pgPaddingItems['spring'];

        if (!empty($pgPaddingItems['social_prefix']))
            $pgTrueItems[$pgPaddingItems['social_prefix']] = $pgPaddingItems['social_id'];

        if (!empty($pgPaddingItems['facebook']))
            $pgTrueItems['facebook'] = $pgPaddingItems['facebook'];

        if (!empty($pgPaddingItems['google']))
            $pgTrueItems['google'] = $pgPaddingItems['google'];

        if (!empty($pgPaddingItems['microsoft']))
            $pgTrueItems['microsoft'] = $pgPaddingItems['microsoft'];

        if (!empty($pgPaddingItems['last_login']))
            $pgTrueItems['last_login'] = $pgPaddingItems['last_login'];

        if (!empty($pgPaddingItems['last_active']))
            $pgTrueItems['last_active'] = $pgPaddingItems['last_active'];

        if (!empty($pgPaddingItems['country']))
            $pgTrueItems['country'] = $pgPaddingItems['country'];

        if (!empty($pgPaddingItems['city']))
            $pgTrueItems['city'] = $pgPaddingItems['city'];

        if (!empty($pgPaddingItems['bio']))
            $pgTrueItems['bio'] = $pgPaddingItems['bio'];

        if (!empty($pgPaddingItems['slogan']))
            $pgTrueItems['slogan'] = $pgPaddingItems['slogan'];

        if (!empty($pgPaddingItems['ext_info']))
            $pgTrueItems['ext_info'] = $pgPaddingItems['ext_info'];

        if (!empty($pgPaddingItems['lat']))
            $pgTrueItems['lat'] = $pgPaddingItems['lat'];

        if (!empty($pgPaddingItems['lng']))
            $pgTrueItems['lng'] = $pgPaddingItems['lng'];

        if (!empty($pgPaddingItems['created_at']))
            $pgTrueItems['created_at'] = $pgPaddingItems['created_at'];

        if (!empty($pgPaddingItems['updated_at']))
            $pgTrueItems['updated_at'] = $pgPaddingItems['updated_at'];

        if (!empty($pgPaddingItems['updated_at']))
            $pgTrueItems['updated_at'] = $pgPaddingItems['updated_at'];

        // items =====================================

        if (!empty($pgPaddingItems['item_id']))
            $pgTrueItems['item_id'] = $pgPaddingItems['item_id'];

        if (!empty($pgPaddingItems['owner_id']))
            $pgTrueItems['owner_id'] = $pgPaddingItems['owner_id'];

        if (!empty($pgPaddingItems['type']))
            $pgTrueItems['type'] = $pgPaddingItems['type'];

        if (!empty($pgPaddingItems['title']))
            $pgTrueItems['title'] = $pgPaddingItems['title'];

        if (!empty($pgPaddingItems['content']))
            $pgTrueItems['content'] = $pgPaddingItems['content'];

        if (!empty($pgPaddingItems['video_duration']))
            $pgTrueItems['video_duration'] = $pgPaddingItems['video_duration'];

        if (!empty($pgPaddingItems['width']))
            $pgTrueItems['width'] = $pgPaddingItems['width'];

        if (!empty($pgPaddingItems['height']))
            $pgTrueItems['height'] = $pgPaddingItems['height'];

        if (!empty($pgPaddingItems['category']))
            $pgTrueItems['category'] = $pgPaddingItems['category'];

        if (!empty($pgPaddingItems['status']))
            $pgTrueItems['status'] = $pgPaddingItems['status'];

        if (!empty($pgPaddingItems['status']))
            $pgTrueItems['status'] = $pgPaddingItems['status'];

        if (!empty($pgPaddingItems['cover']))
            $pgTrueItems['cover'] = $pgPaddingItems['cover'];

        if (!empty($pgPaddingItems['body']))
            $pgTrueItems['body'] = $pgPaddingItems['body'];

        if (!empty($pgPaddingItems['tags']))
            $pgTrueItems['tags'] = $pgPaddingItems['tags'];

        if (!empty($pgPaddingItems['count_show']))
            $pgTrueItems['count_show'] = $pgPaddingItems['count_show'];

        if (!empty($pgPaddingItems['likes_count']))
            $pgTrueItems['likes_count'] = $pgPaddingItems['likes_count'];

        if (!empty($pgPaddingItems['its_like']))
            $pgTrueItems['its_like'] = $pgPaddingItems['its_like'];

        if (!empty($pgPaddingItems['reposts_count']))
            $pgTrueItems['reposts_count'] = $pgPaddingItems['reposts_count'];

        if (!empty($pgPaddingItems['ext_links']))
            $pgTrueItems['ext_links'] = $pgPaddingItems['ext_links'];

        if (!empty($pgPaddingItems['src']))
            $pgTrueItems['src'] = $pgPaddingItems['src'];

        // events =====================================

        if (!empty($pgPaddingItems['cover_video']))
            $pgTrueItems['cover_video'] = $pgPaddingItems['cover_video'];

        if (!empty($pgPaddingItems['started_at']))
            $pgTrueItems['started_at'] = $pgPaddingItems['started_at'];

        if (!empty($pgPaddingItems['stopped_at']))
            $pgTrueItems['stopped_at'] = $pgPaddingItems['stopped_at'];

        if (!empty($pgPaddingItems['item_country']))
            $pgTrueItems['country'] = $pgPaddingItems['item_country'];

        if (!empty($pgPaddingItems['item_city']))
            $pgTrueItems['city'] = $pgPaddingItems['item_city'];

        if (!empty($pgPaddingItems['place']))
            $pgTrueItems['place'] = $pgPaddingItems['place'];

        // messages =====================================

        if (!empty($pgPaddingItems['message_id']))
            $pgTrueItems['message_id'] = $pgPaddingItems['message_id'];

        if (!empty($pgPaddingItems['to_user_id']))
            $pgTrueItems['to_user_id'] = $pgPaddingItems['to_user_id'];

        if (!empty($pgPaddingItems['from_user_id']))
            $pgTrueItems['from_user_id'] = $pgPaddingItems['from_user_id'];

        if (!empty($pgPaddingItems['select_to_user_id']))
            $pgTrueItems['select_to_user_id'] = $pgPaddingItems['select_to_user_id'];

        if (!empty($pgPaddingItems['select_from_user_id']))
            $pgTrueItems['select_from_user_id'] = $pgPaddingItems['select_from_user_id'];

        if (!empty($pgPaddingItems['read_date']))
            $pgTrueItems['read_date'] = $pgPaddingItems['read_date'];

        if (!empty($pgPaddingItems['connect']))
            $pgTrueItems['connect'] = $pgPaddingItems['connect'];

        // signs =====================================

        //if (!empty($pgPaddingItems['sign_id']))
        //    $pgTrueItems['sign_id'] = $pgPaddingItems['sign_id'];

        // Albums =====================================

        if (!empty($pgPaddingItems['album_id']))
            $pgTrueItems['album_id'] = $pgPaddingItems['album_id'];

        if (!empty($pgPaddingItems['albums_sets_id']))
            $pgTrueItems['albums_sets_id'] = $pgPaddingItems['albums_sets_id'];

        // posts =====================================

        if (!empty($pgPaddingItems['post_id']))
            $pgTrueItems['post_id'] = $pgPaddingItems['post_id'];

        if (!empty($pgPaddingItems['post_owner_id']))
            $pgTrueItems['post_owner_id'] = $pgPaddingItems['post_owner_id'];

        // pairs =====================================

        if (!empty($pgPaddingItems['pair_id']))
            $pgTrueItems['pair_id'] = $pgPaddingItems['pair_id'];

        if (!empty($pgPaddingItems['prev_item_id']))
            $pgTrueItems['prev_item_id'] = $pgPaddingItems['prev_item_id'];

        if (!empty($pgPaddingItems['prev_post_id']))
            $pgTrueItems['prev_post_id'] = $pgPaddingItems['prev_post_id'];

        if (!empty($pgPaddingItems['prev_user_id']))
            $pgTrueItems['prev_user_id'] = $pgPaddingItems['prev_user_id'];

        if (!empty($pgPaddingItems['prev_sign_id']))
            $pgTrueItems['prev_sign_id'] = $pgPaddingItems['prev_sign_id'];

        if (!empty($pgPaddingItems['next_item_id']))
            $pgTrueItems['next_item_id'] = $pgPaddingItems['next_item_id'];

        if (!empty($pgPaddingItems['next_post_id']))
            $pgTrueItems['next_post_id'] = $pgPaddingItems['next_post_id'];

        if (!empty($pgPaddingItems['next_user_id']))
            $pgTrueItems['next_user_id'] = $pgPaddingItems['next_user_id'];

        if (!empty($pgPaddingItems['next_sign_id']))
            $pgTrueItems['next_sign_id'] = $pgPaddingItems['next_sign_id'];

        if (!empty($pgPaddingItems['pair_count_show']))
            $pgTrueItems['pair_count_show'] = $pgPaddingItems['pair_count_show'];

        // counts =====================================

        if (!empty($pgPaddingItems['count_item_id']))
            $pgTrueItems['count_item_id'] = $pgPaddingItems['count_item_id'];

        if (!empty($pgPaddingItems['item_count_show']))
            $pgTrueItems['item_count_show'] = $pgPaddingItems['item_count_show'];

        // relationships =====================================

        if (!empty($pgPaddingItems['relation_id']))
            $pgTrueItems['relation_id'] = $pgPaddingItems['relation_id'];

        if (!empty($pgPaddingItems['relation']))
            $pgTrueItems['relation'] = $pgPaddingItems['relation'];

        if (!empty($pgPaddingItems['relation_email']))
            $pgTrueItems['relation_email'] = $pgPaddingItems['relation_email'];

        // tasks =====================================

        if (!empty($pgPaddingItems['task_id']))
            $pgTrueItems['task_id'] = $pgPaddingItems['task_id'];

        if (!empty($pgPaddingItems['task_type']))
            $pgTrueItems['task_type'] = $pgPaddingItems['task_type'];

        if (!empty($pgPaddingItems['task_status']))
            $pgTrueItems['task_status'] = $pgPaddingItems['task_status'];

        if (!empty($pgPaddingItems['attempt']))
            $pgTrueItems['attempt'] = $pgPaddingItems['attempt'];

        if (!empty($pgPaddingItems['file_size_start']))
            $pgTrueItems['file_size_start'] = $pgPaddingItems['file_size_start'];

        if (!empty($pgPaddingItems['file_size_done']))
            $pgTrueItems['file_size_done'] = $pgPaddingItems['file_size_done'];

        if (!empty($pgPaddingItems['file']))
            $pgTrueItems['file'] = $pgPaddingItems['file'];

        if (!empty($pgPaddingItems['file_type']))
            $pgTrueItems['file_type'] = $pgPaddingItems['file_type'];

        if (!empty($pgPaddingItems['task_item_id']))
            $pgTrueItems['task_item_id'] = $pgPaddingItems['task_item_id'];

        if (!empty($pgPaddingItems['access']))
            $pgTrueItems['access'] = $pgPaddingItems['access'];

        if (!empty($pgPaddingItems['from_user_name']))
            $pgTrueItems['from_user_name'] = $pgPaddingItems['from_user_name'];

        if (!empty($pgPaddingItems['from_user_email']))
            $pgTrueItems['from_user_email'] = $pgPaddingItems['from_user_email'];

        if (!empty($pgPaddingItems['lang']))
            $pgTrueItems['lang'] = $pgPaddingItems['lang'];

        if (!empty($pgPaddingItems['to_user_email']))
            $pgTrueItems['to_user_email'] = $pgPaddingItems['to_user_email'];

        if (!empty($pgPaddingItems['cover_upload']))
            $pgTrueItems['cover_upload'] = $pgPaddingItems['cover_upload'];

        if (!empty($pgPaddingItems['parent_id']))
            $pgTrueItems['parent_id'] = $pgPaddingItems['parent_id'];

        // friendship =====================================

        if (!empty($pgPaddingItems['friendship_id']))
            $pgTrueItems['friendship_id'] = $pgPaddingItems['friendship_id'];

        if (!empty($pgPaddingItems['action_user_id']))
            $pgTrueItems['action_user_id'] = $pgPaddingItems['action_user_id'];

        // likes =====================================

        if (!empty($pgPaddingItems['like_id']))
            $pgTrueItems['like_id'] = $pgPaddingItems['like_id'];

        // Reposts =====================================

        if (!empty($pgPaddingItems['repost_id']))
            $pgTrueItems['repost_id'] = $pgPaddingItems['repost_id'];

        // Service =====================================

        if (!empty($pgPaddingItems['users_service_id']))
            $pgTrueItems['users_service_id'] = $pgPaddingItems['users_service_id'];

        if (!empty($pgPaddingItems['service_id']))
            $pgTrueItems['service_id'] = $pgPaddingItems['service_id'];

        // Talents =====================================

        if (!empty($pgPaddingItems['users_talent_id']))
            $pgTrueItems['users_talent_id'] = $pgPaddingItems['users_talent_id'];

        if (!empty($pgPaddingItems['talent_id']))
            $pgTrueItems['talent_id'] = $pgPaddingItems['talent_id'];

        // Stars =====================================

        if (!empty($pgPaddingItems['ui_view_id']))
            $pgTrueItems['ui_view_id'] = $pgPaddingItems['ui_view_id'];

        if (!empty($pgPaddingItems['views_stars']))
            $pgTrueItems['views_stars'] = $pgPaddingItems['views_stars'];

        if (!empty($pgPaddingItems['star_id']))
            $pgTrueItems['star_id'] = $pgPaddingItems['star_id'];

        // Send stat =====================================

        if (!empty($pgPaddingItems['send_rating_period']))
            $pgTrueItems['send_rating_period'] = $pgPaddingItems['send_rating_period'];

        if (!empty($pgPaddingItems['dont_send_rating']))
            $pgTrueItems['dont_send_rating'] = $pgPaddingItems['dont_send_rating'];

        if (!empty($pgPaddingItems['send_stats_period']))
            $pgTrueItems['send_stats_period'] = $pgPaddingItems['send_stats_period'];

        if (!empty($pgPaddingItems['dont_send_stats']))
            $pgTrueItems['dont_send_stats'] = $pgPaddingItems['dont_send_stats'];
        //if (!empty($pgPaddingItems['dont_send_rating']))
        //if (!empty($pgPaddingItems['dont_send_rating']) or $pgPaddingItems['dont_send_rating'] == intval(false))
        //if (!empty($pgPaddingItems['dont_send_rating']) or is_bool($pgPaddingItems['dont_send_rating'])) {
        /*if (is_bool($pgPaddingItems['$pgPaddingItems'])) {
            echo "\npgPaddingItems\n";
            print_r($pgPaddingItems);
            $pgTrueItems['dont_send_rating'] = $pgPaddingItems['dont_send_rating'];
        }*/
        if (!empty($pgPaddingItems['last_rating']))
            $pgTrueItems['last_rating'] = $pgPaddingItems['last_rating'];

        if (!empty($pgPaddingItems['stats_my_rating_next_at']))
            $pgTrueItems['stats_my_rating_next_at'] = $pgPaddingItems['stats_my_rating_next_at'];

        if (!empty($pgPaddingItems['stats_my_items_last_at']))
            $pgTrueItems['stats_my_items_last_at'] = $pgPaddingItems['stats_my_items_last_at'];

        // Comments
        if (!empty($pgPaddingItems['comment_id']))
            $pgTrueItems['comment_id'] = $pgPaddingItems['comment_id'];

        // stars tags
        if (!empty($pgPaddingItems['uit_view_id']))
            $pgTrueItems['uit_view_id'] = $pgPaddingItems['uit_view_id'];

        if (!empty($pgPaddingItems['tag']))
            $pgTrueItems['tag'] = $pgPaddingItems['tag'];

        if (!empty($pgPaddingItems['uit_set_id']))
            $pgTrueItems['uit_set_id'] = $pgPaddingItems['uit_set_id'];

        if (!empty($pgPaddingItems['it_id']))
            $pgTrueItems['it_id'] = $pgPaddingItems['it_id'];

        if (!empty($pgPaddingItems['ut_id']))
            $pgTrueItems['ut_id'] = $pgPaddingItems['ut_id'];

        if (!empty($pgPaddingItems['tag_count']))
            $pgTrueItems['tag_count'] = $pgPaddingItems['tag_count'];

        if (!empty($pgPaddingItems['tit_id']))
            $pgTrueItems['tit_id'] = $pgPaddingItems['tit_id'];

        if (!empty($pgPaddingItems['el_it_id'])) // TODO: temp
            $pgTrueItems['el_it_id'] = $pgPaddingItems['el_it_id'];
        /* Essence ******************************************************************************/
        if (!empty($pgPaddingItems['essence_id']))
            $pgTrueItems['essence_id'] = $pgPaddingItems['essence_id'];
        if (!empty($pgPaddingItems['ue_id']))
            $pgTrueItems['ue_id'] = $pgPaddingItems['ue_id'];
        if (!empty($pgPaddingItems['ure_id']))
            $pgTrueItems['ure_id'] = $pgPaddingItems['ure_id'];
        if (!empty($pgPaddingItems['users_essences']))
            $pgTrueItems['users_essences'] = $pgPaddingItems['users_essences'];
        /* Partners ******************************************************************************/
        if (!empty($pgPaddingItems['ip_id']))
            $pgTrueItems['ip_id'] = $pgPaddingItems['ip_id'];
        if (!empty($pgPaddingItems['partner_id']))
            $pgTrueItems['partner_id'] = $pgPaddingItems['partner_id'];
        /* Lists ******************************************************************************/
        if (!empty($pgPaddingItems['li_id']))
            $pgTrueItems['li_id'] = $pgPaddingItems['li_id'];
        if (!empty($pgPaddingItems['dynamic']))
            $pgTrueItems['dynamic'] = $pgPaddingItems['dynamic'];
        if (!empty($pgPaddingItems['title_vector']))
            $pgTrueItems['title_vector'] = $pgPaddingItems['title_vector'];
        if (!empty($pgPaddingItems['content_vector']))
            $pgTrueItems['content_vector'] = $pgPaddingItems['content_vector'];
        if (!empty($pgPaddingItems['items_array']))
            $pgTrueItems['items_array'] = $pgPaddingItems['items_array'];
        /*if (!empty($pgPaddingItems['src_array']))
            $pgTrueItems['src_array'] = $pgPaddingItems['src_array'];*/
        if (!empty($pgPaddingItems['covers_array']))
            $pgTrueItems['covers_array'] = $pgPaddingItems['covers_array'];
        if (!empty($pgPaddingItems['titles_array']))
            $pgTrueItems['titles_array'] = $pgPaddingItems['titles_array'];
        if (!empty($pgPaddingItems['contents_array']))
            $pgTrueItems['contents_array'] = $pgPaddingItems['contents_array'];
        if (!empty($pgPaddingItems['authors_array']))
            $pgTrueItems['authors_array'] = $pgPaddingItems['authors_array'];
        if (!empty($pgPaddingItems['springs_array']))
            $pgTrueItems['springs_array'] = $pgPaddingItems['springs_array'];
        if (!empty($pgPaddingItems['tags_array']))
            $pgTrueItems['tags_array'] = $pgPaddingItems['tags_array'];
        if (!empty($pgPaddingItems['list_count_show']))
            $pgTrueItems['list_count_show'] = $pgPaddingItems['list_count_show'];
        if (!empty($pgPaddingItems['latest_at']))
            $pgTrueItems['latest_at'] = $pgPaddingItems['latest_at'];
        /* GeoIp2 ******************************************************************************/
        if (!empty($pgPaddingItems['iv_id']))
            $pgTrueItems['iv_id'] = $pgPaddingItems['iv_id'];
        if (!empty($pgPaddingItems['continent']))
            $pgTrueItems['continent'] = $pgPaddingItems['continent'];
        if (!empty($pgPaddingItems['country']))
            $pgTrueItems['country'] = $pgPaddingItems['country'];
        if (!empty($pgPaddingItems['state']))
            $pgTrueItems['state'] = $pgPaddingItems['state'];
        if (!empty($pgPaddingItems['city']))
            $pgTrueItems['city'] = $pgPaddingItems['city'];
        if (!empty($pgPaddingItems['area']))
            $pgTrueItems['area'] = $pgPaddingItems['area'];
        /* Deleting ******************************************************************************/
        if (!empty($pgPaddingItems['fud_id']))
            $pgTrueItems['fud_id'] = $pgPaddingItems['fud_id'];
        /* Trend Maker ******************************************************************************/
        if (!empty($pgPaddingItems['period_now']))
            $pgTrueItems['period_now'] = $pgPaddingItems['period_now'];
        if (!empty($pgPaddingItems['rise_start']))
            $pgTrueItems['rise_start'] = $pgPaddingItems['rise_start'];
        if (!empty($pgPaddingItems['rise_stop']))
            $pgTrueItems['rise_stop'] = $pgPaddingItems['rise_stop'];
        if (!empty($pgPaddingItems['fall_start']))
            $pgTrueItems['fall_start'] = $pgPaddingItems['fall_start'];
        if (!empty($pgPaddingItems['fall_stop']))
            $pgTrueItems['fall_stop'] = $pgPaddingItems['fall_stop'];
        if (!empty($pgPaddingItems['rise_count_show']))
            $pgTrueItems['rise_count_show'] = $pgPaddingItems['rise_count_show'];
        if (!empty($pgPaddingItems['fall_count_show']))
            $pgTrueItems['fall_count_show'] = $pgPaddingItems['fall_count_show'];
        if (!empty($pgPaddingItems['sum_count_show']))
            $pgTrueItems['sum_count_show'] = $pgPaddingItems['sum_count_show'];

        return $pgTrueItems;
    }

}