<?php
class SettingsModel extends CI_Model
{
    public function getUserByUsername($username)
    {
        $this->db->where('username', $username);
        return $this->db->get('o_users')->row(); // single row object
    }
    public function profileImage()
    {
        $id = $this->session->userdata('IDNumber');
        $this->db->where('IDNumber', $id);
        $query = $this->db->get('o_users', 1);
        return $query->result(); // we will use $letterhead[0] in the view
    }


    function activityList($user_id)
    {
        $this->db->select(
            'ls_activities.*,
        (SELECT COUNT(1) FROM proc_pr WHERE proc_pr.actID = ls_activities.actID) AS pr_count',
            false
        );
        $this->db->where('user_id', $user_id);
        $this->db->order_by('actID', 'DESC');
        $query = $this->db->get('ls_activities');
        return $query->result();
    }

    function activityListAll()
    {
        $this->db->select(
            'ls_activities.*,
        (SELECT COUNT(1) FROM proc_pr WHERE proc_pr.actID = ls_activities.actID) AS pr_count',
            false
        );
        $this->db->order_by('actID', 'DESC');
        $query = $this->db->get('ls_activities');
        return $query->result();
    }


    function user_details($user_id)
    {
        $this->db->select('*');
        $this->db->where('username', $user_id);
        $query = $this->db->get('o_users');
        return $query->result();
    }

    public function getActById($actID)
    {
        $this->db->where('actID', $actID);
        $query = $this->db->get('ls_activities');
        return $query->result();
    }




    public function getbacHistory($actID)
    {
        $this->db->select('*'); // You can specify columns if needed
        $this->db->from('ls_activities');
        $this->db->join('bac_tracking', 'bac_tracking.actID = ls_activities.actID', 'left'); // Use 'left' join or 'inner' depending on your needs
        $this->db->where('ls_activities.actID', $actID);
        $query = $this->db->get();
        return $query->result();
    }


    public function generateNextPrNo()
    {
        // Keep numbering consistent by month/year
        date_default_timezone_set('Asia/Manila');
        $current_year = date('Y');
        $current_month = date('m');

        $latest_pr = $this->db->select('prNo')
            ->from('ls_activities')
            ->like('prNo', "$current_year-$current_month-", 'after')
            ->order_by('actID', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if ($latest_pr && !empty($latest_pr->prNo) && preg_match('/(\d{4})$/', $latest_pr->prNo, $matches)) {
            $last_number = intval($matches[1]) + 1;
            $new_number = str_pad($last_number, 4, '0', STR_PAD_LEFT);
        } else {
            $new_number = '0001';
        }

        return "$current_year-$current_month-$new_number";
    }


    function activity_port($actStatus = 'For Action', $userId = null)
    {
        if ($actStatus === null || $actStatus === '') {
            $actStatus = 'For Action';
        }

        $this->db->select('*');
        $this->db->from('ls_activities');

        if (!empty($actStatus)) {
            $this->db->where('actStatus', $actStatus);
        }

        if (!empty($userId)) {
            $this->db->where('user_id', $userId);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function countActivities($userId = null)
    {
        $this->db->from('ls_activities');

        if (!empty($userId)) {
            $this->db->where('user_id', $userId);
        }

        return (int)$this->db->count_all_results();
    }

    public function countUpcomingActivities($userId = null)
    {
        $today = date('Y-m-d');
        $this->db->from('ls_activities');
        $this->db->where('act_date >=', $today);

        if (!empty($userId)) {
            $this->db->where('user_id', $userId);
        }

        return (int)$this->db->count_all_results();
    }

    public function countActivitiesByStatus($status, $userId = null)
    {
        $this->db->from('ls_activities');
        $this->db->where('actStatus', $status);

        if (!empty($userId)) {
            $this->db->where('user_id', $userId);
        }

        return (int)$this->db->count_all_results();
    }

    public function getDashboardCounts($userId = null, $level = 'Employee')
    {
        $filterUser = ($level === 'Employee');
        $userFilterId = $filterUser ? $userId : null;

        return [
            'totalActivities'    => $this->countActivities($userFilterId),
            'upcomingActivities' => $this->countUpcomingActivities($userFilterId),
            'pendingPRs'         => $this->countActivitiesByStatus('For Action', $userFilterId),
            'approvedPRs'        => $this->countActivitiesByStatus('Processed', $userFilterId),
        ];
    }


    public function updateActivityStatus($actID, $status)
    {
        $this->db->where('actID', $actID);
        return $this->db->update('ls_activities', ['actStatus' => $status]);
    }














    function PRList($actID, $user_id)
    {
        $this->db->select('*');
        $this->db->from('ls_activities');
        $this->db->join('proc_pr', 'ls_activities.actID = proc_pr.actID', 'left');
        $this->db->where('ls_activities.actID', $actID);
        $this->db->where('ls_activities.user_id', $user_id);
        $this->db->order_by('proc_pr.lot', 'ASC'); // Order by 'lot' instead of grouping
        $this->db->order_by('ls_activities.actID', 'DESC');
        $this->db->group_by('proc_pr.item_description');

        $query = $this->db->get();
        return $query->result();
    }

    function PRListAll($actID)
    {
        $this->db->select('*');
        $this->db->from('ls_activities');
        $this->db->join('proc_pr', 'ls_activities.actID = proc_pr.actID', 'left');
        $this->db->where('ls_activities.actID', $actID);
        $this->db->order_by('proc_pr.lot', 'ASC'); // Order by 'lot' instead of grouping
        $this->db->order_by('ls_activities.actID', 'DESC');
        $this->db->group_by('proc_pr.item_description');

        $query = $this->db->get();
        return $query->result();
    }

    function PRListAllreq($actID, $lot = null)
    {
        $this->db->select('*');
        $this->db->from('ls_activities');
        $this->db->join('proc_pr', 'ls_activities.actID = proc_pr.actID', 'left');
        $this->db->join('proc_rfq', 'ls_activities.actID = proc_rfq.actID', 'left');
        $this->db->order_by('proc_rfq.rfq_lot_no', 'ASC');
        $this->db->order_by('proc_pr.lot', 'ASC');
        $this->db->order_by('ls_activities.actID', 'DESC');
        $this->db->group_by('proc_pr.item_description');

        $this->db->where('ls_activities.actID', $actID);

        // Add condition for lot only if it's provided
        if (!empty($lot) && $lot !== 'N/A') {
            $this->db->where('proc_pr.lot', $lot);
        }

        $query = $this->db->get();
        return $query->result();
    }






    public function savePrintData($data)
    {
        // Try to insert the data
        $this->db->insert('proc_rfq', $data);

        // Check if the insert was successful
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            // Log error details for debugging
            log_message('error', 'Insert failed: ' . $this->db->last_query());  // Log the last executed query
            return false;
        }
    }




    public function getJoinedData()
    {
        $this->db->select('
            proc_pr.requested_by,
            users.IDNumber,
            users.fName,
            users.lName,
            users.MName,
            users.empPosition,
            users.email
        '); // Specify the columns you want to select
        $this->db->from('proc_pr');
        $this->db->join('users', 'proc_pr.requested_by = users.IDNumber'); // Join condition
        $query = $this->db->get(); // Execute the query
        return $query->result(); // Return the result as an array of objects
    }


    // public function getPR($actID)
    // {
    //     $this->db->where('actID', $actID);
    //     $query = $this->db->get('ls_activities');
    //     return $query->result();
    // }

    public function getPRbyID($prID)
    {
        $query = $this->db->query("SELECT * FROM  proc_pr WHERE prID = '" . $prID . "'");
        return $query->result();
    }



    public function updatePR($prID, $lot, $item_description, $unit, $qty, $est_cost, $actID)
    {
        $data = array(
            'lot' => $lot,
            'item_description' => $item_description,
            'unit' => $unit,
            'qty' => $qty,
            'est_cost' => $est_cost,
            'actID' => $actID  // Ensure actID is part of the data
        );

        $this->db->where('prID', $prID);
        $this->db->update('proc_pr', $data);
    }



    public function deletePR($prID)
    {
        $this->db->where('prID', $prID);
        return $this->db->delete('proc_pr');  // Returns TRUE on success, FALSE on failure
    }

    public function getPRbyID1($prID)
    {
        return $this->db->get_where('proc_pr', ['prID' => $prID])->row();
    }

    public function insertPR($data)
    {
        return $this->db->insert('proc_pr', $data);
    }

    public function getuserById($IDNumber)
    {
        $this->db->select('o_users.*, users.empPosition');
        $this->db->from('o_users');
        $this->db->join('users', 'users.IDNumber = o_users.IDNumber', 'left');
        $this->db->where('o_users.IDNumber', $IDNumber);
        $query = $this->db->get();
        return $query->result();
    }


    public function updateUser($IDNumber, $userData)
    {
        // Update o_users
        $this->db->where('IDNumber', $IDNumber);
        $ok1 = $this->db->update('o_users', $userData);

        // Also update users table to keep data in sync
        $userDataUsers = array(
            'fName'       => $userData['fName']       ?? null,
            'mName'       => $userData['mName']       ?? null,
            'lName'       => $userData['lName']       ?? null,
            'email'       => $userData['email']       ?? null,
            'position'    => $userData['position']    ?? null,
            'empPosition' => $userData['empPosition'] ?? null,
        );

        $this->db->where('IDNumber', $IDNumber);
        $ok2 = $this->db->update('users', $userDataUsers);

        if ($ok1 === false || $ok2 === false) {
            log_message('error', 'Failed to update user (o_users/users) for IDNumber=' . $IDNumber
                . ' | Last query: ' . $this->db->last_query());
            return false;
        }

        // ✅ Treat as success even if affected_rows is 0 (no changes)
        return true;
    }


    function getUsers()
    {
        $this->db->select('*');
        $query = $this->db->get('o_users');
        return $query->result();
    }


    function getActivity($actID)
    {
        // Select the required fields for a specific activity by actID
        $this->db->select('fundSource, prNo, act_title, section');
        $this->db->where('actID', $actID); // Filter by actID
        $query = $this->db->get('ls_activities');

        return $query->result(); // Return the activity objects
    }

    public function updateAttachment($prID, $data)
    {
        $this->db->where('prID', $prID);
        return $this->db->update('proc_pr', $data);
    }


    function proc_rfq($actID)
    {
        // Select the required fields for a specific activity by actID
        $this->db->select('*');
        $this->db->where('actID', $actID); // Filter by actID
        $query = $this->db->get('proc_rfq');

        return $query->result(); // Return the activity objects
    }








    public function insertTracking($trackingData)
    {
        $this->db->insert('bac_tracking', $trackingData);
    }




















    function getStrand1()
    {
        $query = $this->db->query("select * from track_strand group by track order by track");
        return $query->result();
    }

    //Get Track and Display on the combo box
    function getTrack()
    {
        $this->db->select('track');
        $this->db->distinct();
        $this->db->order_by('track', 'ASC');
        $query = $this->db->get('track_strand');
        return $query->result();
    }

    function getStrand($trackVal)
    {
        $this->db->select('track,strand');
        $this->db->where('track', $trackVal);
        $this->db->distinct();
        $this->db->order_by('track', 'ASC');
        $query = $this->db->get('track_strand');
        return $query->result();
    }

    //Get Track List
    function getTrackList()
    {
        $query = $this->db->query("select * from track_strand order by track");
        return $query->result();
    }

    //Get School Information
    function getSchoolInfo()
    {
        $query = $this->db->query("select * from srms_settings limit 1");
        return $query->result();
    }


    function getDocReq()
    {
        $query = $this->db->query("select * from settings_doc_req order by docName");
        return $query->result();
    }


    //Get Section List
    function getSectionList()
    {
        $query = $this->db->query("select * from sections order by Section");
        return $query->result();
    }





    public function get_ethnicity()
    {
        $query = $this->db->get('settings_ethnicity');
        return $query->result();
    }

    public function insertethnicity($data)
    {
        $this->db->insert('settings_ethnicity', $data);
    }

    public function getethnicitybyId($id)
    {
        $query = $this->db->query("SELECT * FROM settings_ethnicity WHERE id = '" . $id . "'");
        return $query->result();
    }

    public function updateethnicity($id, $ethnicity)
    {
        $data = array(
            'ethnicity' => $ethnicity,

        );
        $this->db->where('id', $id);
        $this->db->update('settings_ethnicity', $data);
    }

    public function Delete_ethnicity($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('settings_ethnicity');
    }




    public function get_religion()
    {
        $query = $this->db->get('settings_religion');
        return $query->result();
    }

    public function insertreligion($data)
    {
        $this->db->insert('settings_religion', $data);
    }

    public function getreligionbyId($id)
    {
        $query = $this->db->query("SELECT * FROM settings_religion WHERE id = '" . $id . "'");
        return $query->result();
    }

    public function updatereligion($id, $religion)
    {
        $data = array(
            'religion' => $religion,

        );
        $this->db->where('id', $id);
        $this->db->update('settings_religion', $data);
    }

    public function Delete_religion($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('settings_religion');
    }



    // for prevschool

    public function get_prevschool()
    {
        $query = $this->db->get('prevschool');
        return $query->result();
    }

    public function insertprevschool($data)
    {
        $this->db->insert('prevschool', $data);
    }

    public function getprevschoolbyId($schoolID)
    {
        $query = $this->db->query("SELECT * FROM prevschool WHERE schoolID = '" . $schoolID . "'");
        return $query->result();
    }

    public function updateprevschool($schoolID, $School, $Address)
    {
        $data = array(
            'School' => $School,
            'Address' => $Address,


        );
        $this->db->where('schoolID', $schoolID);
        $this->db->update('prevschool', $data);
    }

    public function Delete_prevschool($schoolID)
    {
        $this->db->where('schoolID', $schoolID);
        $this->db->delete('prevschool');
    }



    public function insertTrack_strand($data)
    {
        $this->db->insert('track_strand', $data);
    }

    public function get_track_strand()
    {
        $query = $this->db->get('track_strand');
        return $query->result();
    }

    public function get_track_strandbyId($trackID)
    {
        $query = $this->db->query("SELECT * FROM track_strand WHERE trackID = '" . $trackID . "'");
        return $query->result();
    }


    public function updatetrack_strand($trackID, $track, $strand)
    {
        $data = array(
            'track' => $track,
            'strand' => $strand,


        );
        $this->db->where('trackID', $trackID);
        $this->db->update('track_strand', $data);
    }


    public function Delete_track_strand($trackID)
    {
        $this->db->where('trackID', $trackID);
        $this->db->delete('track_strand');
    }



    public function insertprogram($data)
    {
        $this->db->insert('course_table', $data);
    }


    public function update_program($courseid, $CourseCode, $CourseDescription, $Major)
    {
        $data = array(
            'CourseCode' => $CourseCode,
            'CourseDescription' => $CourseDescription,
            'Major' => $Major,


        );
        $this->db->where('courseid', $courseid);
        $this->db->update('course_table', $data);
    }


    public function get_programbyId($courseid)
    {
        $query = $this->db->query("SELECT * FROM course_table WHERE courseid = '" . $courseid . "'");
        return $query->result();
    }


    public function Delete_program($courseid)
    {
        $this->db->where('courseid', $courseid);
        $this->db->delete('course_table');
    }


    public function get_subjects()
    {
        $query = $this->db->get('subjects');
        return $query->result();
    }

    public function get_staff()
    {
        $query = $this->db->get('staff');
        return $query->result();
    }

    public function get_year_levels()
    {
        $this->db->distinct();
        $this->db->select('yearLevel');
        $query = $this->db->get('subjects');
        return $query->result();
    }

    public function get_subjects_by_year($yearLevel)
    {
        $this->db->where('yearLevel', $yearLevel);  // Add a where clause to filter by yearLevel
        $query = $this->db->get('subjects');
        return $query->result();  // Return the result as an array of objects
    }



    public function insertsubjects($data)
    {
        $this->db->insert('subjects', $data);
    }


    public function update_subject($id, $subjectCode, $description, $yearLevel, $course, $sem, $subCategory)
    {
        $data = array(
            'subjectCode' => $subjectCode,
            'description' => $description,
            'yearLevel' => $yearLevel,
            'course' => $course,
            'sem' => $sem,
            'subCategory' => $subCategory,

        );
        $this->db->where('id', $id);
        $this->db->update('subjects', $data);
    }

    public function get_subjectbyId($id)
    {
        $query = $this->db->query("SELECT * FROM subjects WHERE id = '" . $id . "'");
        return $query->result();
    }


    public function Delete_subjects($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('subjects');
    }

    public function get_Yearlevels()
    {
        $this->db->distinct();
        $this->db->select('YearLevel');
        $query = $this->db->get('semsubjects');
        return $query->result();
    }

    public function get_subjects_by_yearlevel($YearLevel)
    {
        $this->db->select("semsubjects.*, CONCAT(staff.FirstName, ' ', staff.MiddleName, ' ', staff.LastName) AS Fullname");
        $this->db->from('semsubjects');
        $this->db->join('staff', 'semsubjects.IDNumber = staff.IDNumber', 'left');
        $this->db->where('semsubjects.YearLevel', $YearLevel);
        $query = $this->db->get();

        return $query->result();
    }


    // public function get_classProgram() {
    // 	$query = $this->db->get('semsubjects'); 
    // 	return $query->result();
    // }


    public function get_classProgram()
    {
        $this->db->select("semsubjects.*, CONCAT(staff.FirstName, ' ', staff.MiddleName, ' ', staff.LastName) AS Fullname");
        $this->db->from('semsubjects');
        $this->db->join('staff', 'semsubjects.IDNumber = staff.IDNumber', 'left'); // Left join to include all semsubjects even if no matching staff
        $query = $this->db->get();
        return $query->result();
    }


    public function insertclass($data)
    {
        $this->db->insert('semsubjects', $data);
    }






    public function update_class($subjectid, $SubjectCode, $Description, $Section, $SchedTime, $IDNumber, $SY, $Course, $YearLevel, $SubjectStatus)
    {
        $data = array(
            'subjectid' => $subjectid,
            'SubjectCode' => $SubjectCode,
            'Description' => $Description,
            'Section' => $Section,
            'SchedTime' => $SchedTime,
            'IDNumber' => $IDNumber,
            'SY' => $SY,
            'Course' => $Course,
            'YearLevel' => $YearLevel,
            'SubjectStatus' => $SubjectStatus,

        );
        $this->db->where('subjectid', $subjectid);
        $this->db->update('semsubjects', $data);
    }

    public function get_classbyId($subjectid)
    {
        $query = $this->db->query("SELECT * FROM semsubjects WHERE subjectid = '" . $subjectid . "'");
        return $query->result();
    }

    public function Delete_class($subjectid)
    {
        $this->db->where('subjectid', $subjectid);
        $this->db->delete('semsubjects');
    }


    public function get_Adviser()
    {
        // Join 'staff' table on 'IDNumber'
        $this->db->select('sections.*, staff.*'); // Select columns you need
        $this->db->from('sections');
        $this->db->join('staff', 'staff.IDNumber = sections.IDNumber', 'left'); // Use 'left' join if you want all records from 'sections'

        $query = $this->db->get();
        return $query->result();
    }







    public function insertadviser($data)
    {
        $this->db->insert('sections', $data);
    }

    public function update_Adviser($sectionID, $Section, $IDNumber)
    {
        $data = array(
            'Section' => $Section,
            'IDNumber' => $IDNumber
        );
        $this->db->where('sectionID', $sectionID);
        $this->db->update('sections', $data);
    }



    public function get_adviserbyId($sectionID)
    {
        $query = $this->db->query("SELECT * FROM sections WHERE sections = '" . $sectionID . "'");
        return $query->result();
    }



    public function Delete_adviser($sectionID)
    {
        $this->db->where('sectionID', $sectionID);
        $this->db->delete('sections');
    }



    public function update_Section($sectionID, $Section, $IDNumber)
    {
        $data = array(
            'Section' => $Section,
            'IDNumber' => $IDNumber,

        );
        $this->db->where('sectionID', $sectionID);
        $this->db->update('sections', $data);
    }

    public function Delete_section($sectionID)
    {
        $this->db->where('sectionID', $sectionID);
        $this->db->delete('sections');
    }





    public function get_expenses()
    {
        $query = $this->db->get('expenses');
        return $query->result();
    }

    public function expenses()
    {
        $query = $this->db->get('expenses');
        return $query->result();
    }

    public function insertexpenses($data)
    {
        return $this->db->insert('expenses', $data);
    }



    public function getexpensesbyId($expensesid)
    {
        $query = $this->db->query("SELECT * FROM expenses WHERE expensesid = '" . $expensesid . "'");
        return $query->result();
    }

    public function updateexpenses($expensesid, $Description, $Amount, $Responsible, $ExpenseDate, $Category)
    {
        $data = array(
            'Description' => $Description,
            'Amount' => $Amount,
            'Responsible' => $Responsible,
            'ExpenseDate' => $ExpenseDate,
            'Category' => $Category,



        );
        $this->db->where('expensesid', $expensesid);
        $this->db->update('expenses', $data);
    }

    public function Delete_expenses($expensesid)
    {
        $this->db->where('expensesid', $expensesid);
        $this->db->delete('expenses');
    }


    public function get_expensesCategory()
    {
        $query = $this->db->get('expensescategory');
        return $query->result();
    }

    public function insertexpensesCategory($data)
    {
        return $this->db->insert('expensescategory', $data);
    }

    public function getexpensescategorybyId($categoryID)
    {
        $query = $this->db->query("SELECT * FROM expensescategory WHERE categoryID = '" . $categoryID . "'");
        return $query->result();
    }

    public function updateexpensescategory($categoryID, $Category)
    {
        $data = array(
            'Category' => $Category,
        );
        $this->db->where('categoryID', $categoryID);
        $this->db->update('expensescategory', $data);
    }

    public function Delete_expensescategory($categoryID)
    {
        $this->db->where('categoryID', $categoryID);
        $this->db->delete('expensescategory');
    }


    public function get_categories()
    {
        $this->db->distinct();
        $this->db->select('Category');
        $this->db->from('expenses');
        $query = $this->db->get();
        return $query->result_array(); // Fetches categories as an array
    }


    public function getDescriptionCategories()
    {
        $this->db->distinct();
        $this->db->select('description');
        $query = $this->db->get('paymentsaccounts');
        return $query->result_array();
    }


    public function Payment($SY)
    {
        $this->db->select('paymentsaccounts.*, studeprofile.*');
        $this->db->from('paymentsaccounts');
        $this->db->join('studeprofile', 'studeprofile.StudentNumber = paymentsaccounts.StudentNumber');
        $this->db->where('paymentsaccounts.SY', $SY); // Filter by SY
        $this->db->where('paymentsaccounts.CollectionSource', "Student's Account"); // Filter by CollectionSource
        $query = $this->db->get();
        return $query->result(); // Return the filtered data
    }





    public function services($SY)
    {
        $this->db->select('paymentsaccounts.*, studeprofile.*');
        $this->db->from('paymentsaccounts');
        $this->db->join('studeprofile', 'studeprofile.StudentNumber = paymentsaccounts.StudentNumber');
        $this->db->where('paymentsaccounts.SY', $SY); // Filter by SY
        $this->db->where('paymentsaccounts.CollectionSource', 'Services'); // Filter by CollectionSource = 'Services'
        $query = $this->db->get();
        return $query->result(); // Return the filtered data
    }


    public function Paymentlist($SY)
    {
        $this->db->select('description, SUM(amount) as total_amount, CollectionSource');
        $this->db->from('paymentsaccounts');
        $this->db->where('SY', $SY); // Apply the SY filter
        $this->db->group_by(['description', 'CollectionSource']); // Group by description and CollectionSource
        $query = $this->db->get();
        return $query->result_array(); // Return as an array for easier handling
    }




    // public function getSummaryData($fromDate, $toDate) {
    //     $this->db->select('description, SUM(amount) as total_amount');
    //     $this->db->from('paymentsaccounts');
    //     $this->db->where('Pdate >=', $fromDate);
    //     $this->db->where('Pdate <=', $toDate);
    //     $this->db->group_by(['description', 'CollectionSource']); // Group by description and CollectionSource
    //     $query = $this->db->get();

    //     return $query->result_array();
    // }









    public function getCollectionReport($description, $collectionSource)
    {
        $this->db->select('paymentsaccounts.*, studeprofile.*');
        $this->db->from('paymentsaccounts');
        $this->db->join('studeprofile', 'studeprofile.StudentNumber = paymentsaccounts.StudentNumber');

        // Apply filters for both description and CollectionSource
        if ($description) {
            $this->db->where('paymentsaccounts.description', $description); // Filter by description
        }

        if ($collectionSource) {
            $this->db->where('paymentsaccounts.CollectionSource', $collectionSource); // Filter by CollectionSource
        }

        $query = $this->db->get();
        return $query->result();
    }



    public function semesterstude()
    {
        $query = $this->db->query("
        SELECT semesterstude.*, studeprofile.*
        FROM semesterstude
        JOIN studeprofile ON semesterstude.StudentNumber = studeprofile.StudentNumber
        GROUP BY semesterstude.StudentNumber
        ORDER BY LastName
    ");
        return $query->result();
    }


    public function studeAcc()
    {
        // $query = $this->db->get('studeaccount'); 
        $query = $this->db->query("SELECT * FROM studeaccount");
        return $query->result();
    }

    public function courseTable()
    {
        $query = $this->db->query("SELECT DISTINCT courseid, CourseCode, CourseDescription, Major FROM course_table");
        return $query->result();
    }






    public function user($id)
    {
        $this->db->select('*');
        $this->db->from('o_users');
        $this->db->where('IDNumber', $id);
        $query = $this->db->get();
        return $query->result();
    }



    public function insertpaymentsaccounts($data)
    {
        return $this->db->insert('paymentsaccounts', $data);
    }


    public function getStudentDetails($studentNumber)
    {
        $this->db->select('Course, YearLevel');
        $this->db->from('studeaccount'); // Assuming 'studeaccount' is the table where course and year level are stored
        $this->db->where('StudentNumber', $studentNumber);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row(); // Return the row containing Course and YearLevel
        } else {
            return null; // Handle this case as needed
        }
    }

    public function CourseFees()
    {
        $query = $this->db->get('fees');
        return $query->result();
    }

    public function updateCourseFeesbyId($feesid)
    {
        $query = $this->db->query("SELECT * FROM fees WHERE feesid = '" . $feesid . "'");
        return $query->result();
    }


    public function getYearLevels()
    {
        // Fetch distinct YearLevel from the fees table
        $this->db->select('YearLevel');
        $this->db->distinct();
        $query = $this->db->get('fees');
        return $query->result();
    }

    // public function getFeesByYearLevel($yearLevel)
    // {
    //     // Fetch fees data based on selected YearLevel
    //     $this->db->where('YearLevel', $yearLevel);
    //     $query = $this->db->get('fees');
    //     return $query->result();
    // }


    public function getFeesByYearLevel($yearLevel)
    {
        // Fetch fees data based on selected YearLevel
        $this->db->where('YearLevel', $yearLevel);
        $query = $this->db->get('fees');
        return $query->result();
    }

    public function getTotalFeesByYearLevel($yearLevel)
    {
        // Get total amount for a specific year level
        $this->db->select_sum('Amount');
        $this->db->where('YearLevel', $yearLevel);
        $query = $this->db->get('fees');
        return $query->row()->Amount;
    }

    public function getTotalFees()
    {
        // Get total amount for all year levels
        $this->db->select_sum('Amount');
        $query = $this->db->get('fees');
        return $query->row()->Amount;
    }



    public function updateFees($feesid, $YearLevel, $Course, $Description, $Amount)
    {
        $data = array(
            'YearLevel' => $YearLevel,
            'Course' => $Course,
            'Description' => $Description,
            'Amount' => $Amount
        );
        $this->db->where('feesid', $feesid);
        $this->db->update('fees', $data);
    }

    public function Deletefees($feesid)
    {
        $this->db->where('feesid', $feesid);
        $this->db->delete('fees');
    }

    public function insertfees($data)
    {
        return $this->db->insert('fees', $data);
    }


    public function getDistinctCourses()
    {
        $this->db->select('DISTINCT(Course)');
        $this->db->from('fees');
        $query = $this->db->get();
        return $query->result();
    }

    public function getDistinctYearLevels()
    {
        $this->db->select('DISTINCT(YearLevel)');
        $this->db->from('fees');
        $query = $this->db->get();
        return $query->result();
    }


    public function get_brand()
    {
        $query = $this->db->get('ls_brands');
        return $query->result();
    }

    public function get_brandbyID($brandID)
    {
        $query = $this->db->query("SELECT * FROM ls_brands WHERE brandID = '" . $brandID . "'");
        return $query->result();
    }

    public function insertBrand($data)
    {
        $this->db->insert('ls_brands', $data);
    }

    public function update_brand($brandID, $brand)
    {
        $data = array(
            'brand' => $brand,
        );
        $this->db->where('brandID', $brandID);
        $this->db->update('ls_brands', $data);
    }

    public function Delete_brand($brandID)
    {
        $this->db->where('brandID', $brandID);
        $this->db->delete('ls_brands');
    }

    public function get_category()
    {
        $query = $this->db->get('ls_categories');
        return $query->result();
    }

    public function get_categorybyID($CatNo)
    {
        $query = $this->db->query("SELECT * FROM ls_categories WHERE CatNo = '" . $CatNo . "'");
        return $query->result();
    }

    public function insertCategory($data)
    {
        $this->db->insert('ls_categories', $data);
    }

    public function update_category($CatNo, $Category, $Sub_category)
    {
        $data = array(
            'Category' => $Category,
            'Sub_category' => $Sub_category,
        );
        $this->db->where('CatNo', $CatNo);
        $this->db->update('ls_categories', $data);
    }

    public function Delete_category($CatNo)
    {
        $this->db->where('CatNo', $CatNo);
        $this->db->delete('ls_categories');
    }


    public function get_office()
    {
        $query = $this->db->get('ls_office');
        return $query->result();
    }

    public function get_officebyID($officeID)
    {
        $query = $this->db->query("SELECT * FROM ls_office WHERE officeID = '" . $officeID . "'");
        return $query->result();
    }

    public function insertOffice($data)
    {
        $this->db->insert('ls_office', $data);
    }

    public function update_office($officeID, $office)
    {
        $data = array(
            'office' => $office,
        );
        $this->db->where('officeID', $officeID);
        $this->db->update('ls_office', $data);
    }

    public function Delete_office($officeID)
    {
        $this->db->where('officeID', $officeID);
        $this->db->delete('ls_office');
    }



    function course()
    {
        $this->db->distinct();
        $this->db->select('CourseCode');
        $this->db->from('course_table');
        $this->db->order_by('CourseCode');

        $query = $this->db->get();
        return $query->result();
    }










    // Get fees data based on selected YearLevel and SY
    public function getFeesByYearLevelAndSY($yearLevel, $SY)
    {
        $this->db->where('YearLevel', $yearLevel);
        $this->db->where('SY', $SY); // Filter by logged-in SY
        $query = $this->db->get('fees');
        return $query->result();
    }

    // Get total amount for a specific year level and SY
    public function getTotalFeesByYearLevelAndSY($yearLevel, $SY)
    {
        $this->db->select_sum('Amount');
        $this->db->where('YearLevel', $yearLevel);
        $this->db->where('SY', $SY); // Filter by logged-in SY
        $query = $this->db->get('fees');
        return $query->row()->Amount;
    }

    // Get fees data for the logged-in SY (all year levels)
    public function getCourseFeesBySY($SY)
    {
        $this->db->where('SY', $SY); // Filter by logged-in SY
        $query = $this->db->get('fees');
        return $query->result();
    }

    // Get total amount for all year levels for the logged-in SY
    public function getTotalFeesBySY($SY)
    {
        $this->db->select_sum('Amount');
        $this->db->where('SY', $SY); // Filter by logged-in SY
        $query = $this->db->get('fees');
        return $query->row()->Amount;
    }


    function track()
    {
        $this->db->distinct();
        $this->db->select('track');
        $this->db->from('track_strand');
        $this->db->order_by('track');

        $query = $this->db->get();
        return $query->result();
    }

    function strand()
    {
        $this->db->distinct();
        $this->db->select('strand');
        $this->db->from('track_strand');
        $this->db->order_by('strand');

        $query = $this->db->get();
        return $query->result();
    }

    function GetSub()
    {
        $this->db->distinct();
        $this->db->select('subjectCode');
        $this->db->from('subjects');
        $this->db->order_by('subjectCode');
        $query = $this->db->get();
        return $query->result();
    }


    function GetSub1()
    {
        $this->db->distinct();
        $this->db->select('description');
        $this->db->from('subjects');
        $this->db->order_by('description');
        $query = $this->db->get();
        return $query->result();
    }

    function GetSub2()
    {
        $this->db->distinct();
        $this->db->select('course');
        $this->db->from('subjects');
        $this->db->order_by('course');
        $query = $this->db->get();
        return $query->result();
    }

    function GetSub3()
    {
        $this->db->distinct();
        $this->db->select('yearLevel');
        $this->db->from('subjects');
        $this->db->order_by('yearLevel');
        $query = $this->db->get();
        return $query->result();
    }

    function GetSection()
    {
        $this->db->distinct();
        $this->db->select('Section');
        $this->db->from('sections');
        $this->db->order_by('Section');
        $query = $this->db->get();
        return $query->result();
    }


    public function get_subjects_by_yearlevel1($yearLevel)
    {
        $this->db->select('subjects.*, sections.Section');
        $this->db->from('subjects');
        $this->db->join('sections', 'subjects.yearLevel = sections.YearLevel', 'left');
        $this->db->where('subjects.yearLevel', $yearLevel);
        $query = $this->db->get();
        return $query->result();
    }



    public function get_sections_by_yearlevel($yearLevel)
    {
        $this->db->select('Section');
        $this->db->from('sections');
        $this->db->where('YearLevel', $yearLevel);
        $this->db->order_by('Section', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }
    public function getPortalSettings()
    {
        // Primary: o_srms_settings (your BERPS/BAC portal)
        $row = $this->db->limit(1)->get('o_srms_settings')->row();
        if ($row) {
            return $row;
        }

        // Fallback: srms_settings (old)
        return $this->db->limit(1)->get('srms_settings')->row();
    }

    public function get_recaptcha_keys()
    {
        $row = $this->getPortalSettings();
        if (!$row) {
            return [null, null];
        }

        // EXACT columns from your screenshot
        $site = isset($row->site_key) ? trim($row->site_key) : null;
        $sec  = isset($row->sec_key)  ? trim($row->sec_key)  : null;

        if ($site === '') {
            $site = null;
        }
        if ($sec  === '') {
            $sec  = null;
        }

        return [$site, $sec];
    }
}
