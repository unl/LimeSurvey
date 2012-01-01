<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * LimeSurvey
 * Copyright (C) 2007-2011 The LimeSurvey Project Team / Carsten Schmitz
 * All rights reserved.
 * License: GNU/GPL License v2 or later, see LICENSE.php
 * LimeSurvey is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 *	$Id: Admin_Controller.php 11256 2011-10-25 13:52:18Z c_schmitz $
 */
class SurveyController extends LSYii_Controller
{
	public $lang = null;

	/**
	 * Initialises this controller, does some basic checks and setups
	 *
	 * @access protected
	 * @return void
	 */
	protected function _init()
	{
		parent::_init();
		$updatelastcheck = '';

		$this->_sessioncontrol();

		if (Yii::app()->getConfig('buildnumber') != "" && Yii::app()->getConfig('updatecheckperiod') > 0 && $updatelastcheck < date_shift(date("Y-m-d H:i:s"), "Y-m-d H:i:s", "-". Yii::app()->getConfig('updatecheckperiod')." days"))
			updatecheck();

		unset(Yii::app()->session['FileManagerContext']);

		if (!Yii::app()->getConfig("surveyid")) {Yii::app()->setConfig("surveyid", returnglobal('sid'));}         //SurveyID
		if (!Yii::app()->getConfig("ugid")) {Yii::app()->setConfig("ugid", returnglobal('ugid'));}                //Usergroup-ID
		if (!Yii::app()->getConfig("gid")) {Yii::app()->setConfig("gid", returnglobal('gid'));}                   //GroupID
		if (!Yii::app()->getConfig("qid")) {Yii::app()->setConfig("qid", returnglobal('qid'));}                   //QuestionID
		if (!Yii::app()->getConfig("lid")) {Yii::app()->setConfig("lid", returnglobal('lid'));}                   //LabelID
		if (!Yii::app()->getConfig("code")) {Yii::app()->setConfig("code", returnglobal('code'));}                // ??
		if (!Yii::app()->getConfig("action")) {Yii::app()->setConfig("action", returnglobal('action'));}          //Desired action
		if (!Yii::app()->getConfig("subaction")) {Yii::app()->setConfig("subaction", returnglobal('subaction'));} //Desired subaction
		if (!Yii::app()->getConfig("editedaction")) {Yii::app()->setConfig("editedaction", returnglobal('editedaction'));} // for html editor integration
	}

    /**
	 * Load and set session vars
	 *
	 * @access protected
	 * @return void
	 */
	protected function _sessioncontrol()
	{
		if (!Yii::app()->session["adminlang"] || Yii::app()->session["adminlang"]=='')
			Yii::app()->session["adminlang"] = Yii::app()->getConfig("defaultlang");

		Yii::import('application.libraries.Limesurvey_lang');
		$this->lang = new Limesurvey_lang(array('langcode' => Yii::app()->session['adminlang']));
		Yii::app()->setLang($this->lang);
	}

	/**
	 * Routes all the actions to their respective places
	 *
	 * @access public
	 * @return array
	 */
	public function actions()
	{
		return array(
			'tcpdf_check' => 'application.controllers.tcpdf_check',
            'index' => 'application.controllers.surveyaction',
            'optin' => 'application.controllers.optin',
            'optout' => 'application.controllers.optout',
            'printanswers' => 'application.controllers.printanswers',
            'register' => 'application.controllers.register',
            'statistics_user' => 'application.controllers.statistics_user',
            'uploader' => 'application.controllers.uploader',
            'verification' => 'application.controllers.verification',
		);
	}

        /**
	 * Shows admin menu for question
	 * @param int Survey id
     * @param int Group id
     * @param int Question id
     * @param string action
	 */
     function _questionbar($surveyid,$gid,$qid,$action)
     {

        $clang = Yii::app()->lang;
        Yii::app()->loadHelper('database');
        Yii::app()->loadHelper('text');

        $baselang = Survey::model()->findByPk($surveyid)->language;

        //Show Question Details
    	//Count answer-options for this question
        $qrq = "SELECT * FROM {{answers}} WHERE qid=$qid AND language='".$baselang."' ORDER BY sortorder, answer";
        $qrr = db_execute_assoc($qrq); //Checked)
        $data['qct'] = $qct = $qrr->count();
    	//Count sub-questions for this question
    	$sqrq= "SELECT * FROM {{questions}} WHERE parent_qid=$qid AND language='".$baselang."'";
    	$sqrr= db_execute_assoc($sqrq); //Checked
    	$data['sqct'] = $sqct = $sqrr->count();

        $qrquery = "SELECT * FROM {{questions}} WHERE gid=$gid AND sid=$surveyid AND qid=$qid AND language='".$baselang."'";
        $qrresult = db_execute_assoc($qrquery);  //Checked
        $questionsummary = "<div class='menubar'>\n";

        // Check if other questions in the Survey are dependent upon this question
        $condarray=GetQuestDepsForConditions($surveyid,"all","all",$qid,"by-targqid","outsidegroup");
        $sumresult1 = Surveys::model()->getDataOnSurvey($surveyid);
        if ($sumresult1->count()==0){die('Invalid survey id');} //  if surveyid is invalid then die to prevent errors at a later time
        $surveyinfo = $sumresult1->read();

//        LimeExpressionManager::StartProcessingPage();
//        LimeExpressionManager::StartProcessingGroup($gid,($surveyinfo['anonymized']!="N"),$surveyinfo['sid']);  // loads list of replacement values available for this group

        $surveyinfo = array_map('FlattenText', $surveyinfo);
        $data['activated'] = $surveyinfo['active'];

        foreach ($qrresult->readAll() as $qrrow)
        {
            $qrrow = array_map('FlattenText', $qrrow);
            if(bHasSurveyPermission($surveyid,'surveycontent','read'))
            {
                if (count(Survey::model()->findByPk($surveyid)->additionalLanguages) == 0)
                {
                } else {
                    Yii::app()->loadHelper('surveytranslator');
                    $tmp_survlangs = Survey::model()->findByPk($surveyid)->additionalLanguages;
                    $baselang = Survey::model()->findByPk($surveyid)->language;
                    $tmp_survlangs[] = $baselang;
                    rsort($tmp_survlangs);
                    $data['tmp_survlangs'] = $tmp_survlangs;

                }
            }
            $data['qtypes'] = $qtypes=getqtypelist('','array');
            if ($action=='editansweroptions' || $action =="editsubquestions" || $action =="editquestion" || $action =="editdefaultvalues" || $action =="copyquestion")
            {
                $qshowstyle = "style='display: none'";
            }
            else
            {
                $qshowstyle = "";
            }
            $data['qshowstyle'] = $qshowstyle;
            $data['action'] = $action;
            $data['surveyid'] = $surveyid;
            $data['qid'] = $qid;
            $data['gid'] = $gid;
            $data['clang'] = $clang;
            $data['qrrow'] = $qrrow;
            $data['baselang'] = $baselang;
            $aAttributesWithValues=Questions::model()->getAdvancedSettingsWithValues($qid, $qrrow['type'], $surveyid, $baselang);
            $DisplayArray=array();
            foreach ($aAttributesWithValues as $aAttribute)
            {
                if (($aAttribute['i18n']==false && isset($aAttribute['value']) && $aAttribute['value']!=$aAttribute['default']) || ($aAttribute['i18n']==true && isset($aAttribute['value'][$baselang]) && $aAttribute['value'][$baselang]!=$aAttribute['default']))
                {
                    if ($aAttribute['inputtype']=='singleselect')
                    {
                        $aAttribute['value']=$aAttribute['options'][$aAttribute['value']];
                    }
                    /*
                    if ($aAttribute['name']=='relevance')
                    {
                        $sRelevance = $aAttribute['value'];
                        if ($sRelevance !== '' && $sRelevance !== '1' && $sRelevance !== '0')
                        {
                            LimeExpressionManager::ProcessString("{" . $sRelevance . "}");    // tests Relevance equation so can pretty-print it
                            $aAttribute['value']= LimeExpressionManager::GetLastPrettyPrintExpression();
                        }
                    }
                     */
                    $DisplayArray[]=$aAttribute;
                }
            }
            if (is_null($qrrow['relevance']) || trim($qrrow['relevance']) == '')
            {
                $data['relevance'] = 1;
            }
            else
            {
                LimeExpressionManager::ProcessString("{" . $qrrow['relevance'] . "}", $data['qid']);    // tests Relevance equation so can pretty-print it
                $data['relevance'] = LimeExpressionManager::GetLastPrettyPrintExpression();
            }
            $data['advancedsettings']=$DisplayArray;

            $questionsummary .= $this->getController()->render("admin/survey/Question/questionbar_view",$data,true);
        }
        $finaldata['display'] = $questionsummary;
        $this->getController()->render('survey_view',$finaldata);
     }


    /**
	 * Shows admin menu for question groups
	 * @param int Survey id
     * @param int Group id
	 */
    function _questiongroupbar($surveyid,$gid,$qid=null,$action)
    {

        $clang = Yii::app()->lang;
        Yii::app()->loadHelper('database');
        $baselang = Survey::model()->findByPk($surveyid)->language;

        // TODO: check that surveyid and thus baselang are always set here
        $sumquery4 = "SELECT * FROM {{questions}} WHERE sid=$surveyid AND
    	gid=$gid AND language='".$baselang."'"; //Getting a count of questions for this survey
        $sumresult4 = db_execute_assoc($sumquery4); //Checked
        $sumcount4 = $sumresult4->count();
        $grpquery ="SELECT * FROM {{groups}} WHERE gid=$gid AND
    	language='".$baselang."' ORDER BY {{groups.group_order}}";
        $grpresult = db_execute_assoc($grpquery); //Checked

        // Check if other questions/groups are dependent upon this group
        $condarray=GetGroupDepsForConditions($surveyid,"all",$gid,"by-targgid");

        $groupsummary = "<div class='menubar'>\n"
        . "<div class='menubar-title ui-widget-header'>\n";

        //$sumquery1 = "SELECT * FROM ".db_table_name('surveys')." inner join ".db_table_name('surveys_languagesettings')." on (surveyls_survey_id=sid and surveyls_language=language) WHERE sid=$surveyid"; //Getting data for this survey
        $sumresult1 = Surveys::model()->getDataOnSurvey($surveyid); //$sumquery1, 1) ; //Checked
        if ($sumresult1->count()==0){die('Invalid survey id');} //  if surveyid is invalid then die to prevent errors at a later time
        $surveyinfo = $sumresult1->read();
        $surveyinfo = array_map('FlattenText', $surveyinfo);
        //$surveyinfo = array_map('htmlspecialchars', $surveyinfo);
        $data['activated'] = $activated = $surveyinfo['active'];

        foreach ($grpresult->readAll() as $grow)
        {
            $grow = array_map('FlattenText', $grow);
            $data = array();
            $data['qid'] = $qid;
            $data['QidPrev'] = $QidPrev = getQidPrevious($surveyid, $gid, $qid);
            $data['QidNext'] = $QidNext = getQidNext($surveyid, $gid, $qid);

            if ($action=='editgroup'|| $action=='addquestion' || $action == 'viewquestion' || $action == "editdefaultvalues")
            {
                $gshowstyle="style='display: none'";
            }
            else
            {
                $gshowstyle="";
            }

            $data['gshowstyle'] = $gshowstyle;
            $data['surveyid'] = $surveyid;
            $data['gid'] = $gid;
            $data['grow'] = $grow;
            $data['clang'] = $clang;
            $data['condarray'] = $condarray;
            $data['sumcount4'] = $sumcount4;

            if (!($action == 'addquestion'))
            {
                // This is needed to properly color-code content if it contains replacements
                LimeExpressionManager::StartProcessingGroup($gid,($surveyinfo['anonymized']!="N"),$surveyinfo['sid']);  // loads list of replacement values available for this group
            }

            $groupsummary .= $this->getController()->render('admin/survey/QuestionGroups/questiongroupbar_view',$data,true);
        }
        $groupsummary .= "\n</table>\n";

        $finaldata['display'] = $groupsummary;
        $this->getController()->render('survey_view',$finaldata);

    }

    /**
	 * Shows admin menu for surveys
	 * @param int Survey id
	 */
    function _surveybar($surveyid,$gid=null)
    {
    	//Yii::app()->loadHelper('surveytranslator');
    	$clang = Yii::app()->lang;
		//echo $this->config->item('gid');
        $baselang = Survey::model()->findByPk($surveyid)->language;
        $condition = array('sid' => $surveyid, 'language' => $baselang);
        //$sumquery1 = "SELECT * FROM ".db_table_name('surveys')." inner join ".db_table_name('surveys_languagesettings')." on (surveyls_survey_id=sid and surveyls_language=language) WHERE sid=$surveyid"; //Getting data for this survey
        $sumresult1 = Surveys::model()->getDataOnSurvey($surveyid); //$sumquery1, 1) ; //Checked
        if ($sumresult1->count()==0){die('Invalid survey id');} //  if surveyid is invalid then die to prevent errors at a later time
        $surveyinfo = $sumresult1->read();
        $surveyinfo = array_map('FlattenText', $surveyinfo);
        //$surveyinfo = array_map('htmlspecialchars', $surveyinfo);
        $activated = ($surveyinfo['active']=='Y');

        $js_admin_includes = $this->config->item("js_admin_includes");
        $js_admin_includes[]=$this->config->item('generalscripts').'jquery/jquery.coookie.js';
        $js_admin_includes[]=$this->config->item('generalscripts').'jquery/superfish.js';
        $js_admin_includes[]=$this->config->item('generalscripts').'jquery/hoverIntent.js';
        $js_admin_includes[]=$this->config->item('adminscripts').'surveytoolbar.js';
        $css_admin_includes[] = $this->config->item('styleurl')."admin/default/superfish.css";

		$this->config->set_item("css_admin_includes", $css_admin_includes);
        $this->config->set_item("js_admin_includes", $js_admin_includes);

		//Parse data to send to view
		$data['clang']=$clang;
		$data['surveyinfo']=$surveyinfo;
		$data['surveyid']=$surveyid;

		// ACTIVATE SURVEY BUTTON
		$data['activated'] = $activated;
		$data['imageurl'] = $this->config->item('imageurl');

        $condition = array('sid' => $surveyid, 'parent_qid' => 0, 'language' => $baselang);
        //$sumquery3 =  "SELECT * FROM ".db_table_name('questions')." WHERE sid={$surveyid} AND parent_qid=0 AND language='".$baselang."'"; //Getting a count of questions for this survey
        $sumresult3 = Questions::model()->getAllRecords($condition);  //Checked
        $sumcount3 = $sumresult3->count();

		$data['canactivate'] = $sumcount3 > 0 && bHasSurveyPermission($surveyid,'surveyactivation','update');
		$data['candeactivate'] = bHasSurveyPermission($surveyid,'surveyactivation','update');
		$data['expired'] = $surveyinfo['expires']!='' && ($surveyinfo['expires'] < date_shift(date("Y-m-d H:i:s"), "Y-m-d H:i", $this->config->item('timeadjust')));
		$data['notstarted'] = ($surveyinfo['startdate']!='') && ($surveyinfo['startdate'] > date_shift(date("Y-m-d H:i:s"), "Y-m-d H:i", $this->config->item('timeadjust')));

        // Start of suckerfish menu
        // TEST BUTTON
  		if (!$activated)
        {
            $data['icontext']=$clang->gT("Test This Survey");
            $data['icontext2']=$clang->gTview("Test This Survey");
        } else
        {
            $data['icontext']=$clang->gT("Execute This Survey");
            $data['icontext2']=$clang->gTview("Execute This Survey");
        }

        $data['baselang'] = Survey::model()->findByPk($surveyid)->language;
 		$data['onelanguage'] = (count(Survey::model()->findByPk($surveyid)->additionalLanguages) == 0);

		$tmp_survlangs = Survey::model()->findByPk($surveyid)->additionalLanguages;
		$data['additionallanguages'] = $tmp_survlangs;
        $tmp_survlangs[] = $data['baselang'];
		rsort($tmp_survlangs);
		$data['languagelist'] = $tmp_survlangs;

		$data['hasadditionallanguages']=(count($data['additionallanguages']) > 0);

        // EDIT SURVEY TEXT ELEMENTS BUTTON
        $data['surveylocale']=bHasSurveyPermission($surveyid,'surveylocale','read');
        // EDIT SURVEY SETTINGS BUTTON
        $data['surveysettings']=bHasSurveyPermission($surveyid,'surveysettings','read');
        // Survey permission item
        $data['surveysecurity']=($this->session->userdata('USER_RIGHT_SUPERADMIN') == 1 || $surveyinfo['owner_id'] == $this->session->userdata('loginID'));
         // CHANGE QUESTION GROUP ORDER BUTTON
        $data['surveycontent']=bHasSurveyPermission($surveyid,'surveycontent','read');
		$data['groupsum']=(getGroupSum($surveyid,$surveyinfo['language'])>1);
        // SET SURVEY QUOTAS BUTTON
        $data['quotas'] = bHasSurveyPermission($surveyid,'quotas','read');
        // Assessment menu item
        $data['assessments'] = bHasSurveyPermission($surveyid,'assessments','read');
        // EDIT SURVEY TEXT ELEMENTS BUTTON
        // End if survey properties

        // Tools menu item
        // Delete survey item
        $data['surveydelete'] = bHasSurveyPermission($surveyid,'survey','delete');
        // Translate survey item
        $data['surveytranslate'] = bHasSurveyPermission($surveyid,'translations','read');
        // RESET SURVEY LOGIC BUTTON
        //$sumquery6 = "SELECT count(*) FROM ".db_table_name('conditions')." as c, ".db_table_name('questions')." as q WHERE c.qid = q.qid AND q.sid=$surveyid"; //Getting a count of conditions for this survey
        // TMSW Conditions->Relevance:  How is conditionscount used?  Should Relevance do the same?
        $query = Conditions::model()->getCountOfConditions($surveyid);
        $sumcount6 = $query->read();  //Checked
        $data['surveycontent'] = bHasSurveyPermission($surveyid,'surveycontent','update');
		$data['conditionscount'] = ($sumcount6 > 0);
        // Eport menu item
        $data['surveyexport']=bHasSurveyPermission($surveyid,'surveycontent','export');
        // PRINTABLE VERSION OF SURVEY BUTTON
        // SHOW PRINTABLE AND SCANNABLE VERSION OF SURVEY BUTTON

        //browse responses menu item
        $data['respstatsread']=bHasSurveyPermission($surveyid,'responses','read') || bHasSurveyPermission($surveyid,'statistics','read') || bHasSurveyPermission($surveyid,'responses','export');
        // Data entry screen menu item
        $data['responsescreate']=bHasSurveyPermission($surveyid,'responses','create');
        $data['responsesread'] = bHasSurveyPermission($surveyid,'responses','read');
        // TOKEN MANAGEMENT BUTTON
		$data['tokenmanagement'] = bHasSurveyPermission($surveyid,'surveysettings','update') || bHasSurveyPermission($surveyid,'tokens','read');

        $data['gid'] = $gid ;// = $this->input->post('gid');

        if (bHasSurveyPermission($surveyid,'surveycontent','read'))
        {
            $data['permission']= true;
        }
        else
        {
            $data['gid'] = $gid =null;
            $qid=null;
            $data['permission']= false;
        }

        if (getgrouplistlang($gid, $baselang,$surveyid))
        {
            $data['groups']= getgrouplistlang($gid, $baselang,$surveyid);
        }
        else
        {
            $data['groups']= "<option>".$clang->gT("None")."</option>";
        }

        $data['GidPrev'] = $GidPrev = getGidPrevious($surveyid, $gid);

        $data['GidNext'] = $GidNext = getGidNext($surveyid, $gid);

        $this->getController()->render("admin/survey/surveybar_view",$data);


    }

	/**
	 * Show survey summary
	 * @param int Survey id
     * @param string Action to be performed
	 */
    function _surveysummary($surveyid,$action=null)
    {
        $clang = Yii::app()->lang;

		$baselang = Survey::model()->findByPk($surveyid)->language;
        $condition = array('sid' => $surveyid, 'language' => $baselang);
        //$sumquery1 = "SELECT * FROM ".db_table_name('surveys')." inner join ".db_table_name('surveys_languagesettings')." on (surveyls_survey_id=sid and surveyls_language=language) WHERE sid=$surveyid"; //Getting data for this survey
        $sumresult1 = Surveys::model()->getDataOnSurvey($surveyid); //$sumquery1, 1) ; //Checked
        if ($sumresult1->count()==0){die('Invalid survey id');} //  if surveyid is invalid then die to prevent errors at a later time
        $surveyinfo = $sumresult1->read();
        $surveyinfo = array_map('FlattenText', $surveyinfo);
        //$surveyinfo = array_map('htmlspecialchars', $surveyinfo);
        $activated = $surveyinfo['active'];

		$condition = array('sid' => $surveyid, 'parent_qid' => 0, 'language' => $baselang);
        //$sumquery3 =  "SELECT * FROM ".db_table_name('questions')." WHERE sid={$surveyid} AND parent_qid=0 AND language='".$baselang."'"; //Getting a count of questions for this survey
        $sumresult3 = Questions_model::model()->getAllRecords($condition);  //Checked
        $sumcount3 = $sumresult3->count();

		$condition = array('sid' => $surveyid, 'language' => $baselang);
		//$sumquery2 = "SELECT * FROM ".db_table_name('groups')." WHERE sid={$surveyid} AND language='".$baselang."'"; //Getting a count of groups for this survey
		$sumresult2 = Groups::model()->getAllRecords($condition); //Checked
		$sumcount2 = $sumresult2->count();

        //SURVEY SUMMARY

        $aAdditionalLanguages = Survey::model()->findByPk($surveyid)->additionalLanguages;
        $surveysummary2 = "";
        if ($surveyinfo['anonymized'] != "N") {$surveysummary2 .= $clang->gT("Responses to this survey are anonymized.")."<br />";}
        else {$surveysummary2 .= $clang->gT("Responses to this survey are NOT anonymized.")."<br />";}
        if ($surveyinfo['format'] == "S") {$surveysummary2 .= $clang->gT("It is presented question by question.")."<br />";}
        elseif ($surveyinfo['format'] == "G") {$surveysummary2 .= $clang->gT("It is presented group by group.")."<br />";}
        else {$surveysummary2 .= $clang->gT("It is presented on one single page.")."<br />";}
        if ($surveyinfo['allowjumps'] == "Y")
        {
          if ($surveyinfo['format'] == 'A') {$surveysummary2 .= $clang->gT("No question index will be shown with this format.")."<br />";}
          else {$surveysummary2 .= $clang->gT("A question index will be shown; participants will be able to jump between viewed questions.")."<br />";}
        }
        if ($surveyinfo['datestamp'] == "Y") {$surveysummary2 .= $clang->gT("Responses will be date stamped.")."<br />";}
        if ($surveyinfo['ipaddr'] == "Y") {$surveysummary2 .= $clang->gT("IP Addresses will be logged")."<br />";}
        if ($surveyinfo['refurl'] == "Y") {$surveysummary2 .= $clang->gT("Referrer URL will be saved.")."<br />";}
        if ($surveyinfo['usecookie'] == "Y") {$surveysummary2 .= $clang->gT("It uses cookies for access control.")."<br />";}
        if ($surveyinfo['allowregister'] == "Y") {$surveysummary2 .= $clang->gT("If tokens are used, the public may register for this survey")."<br />";}
        if ($surveyinfo['allowsave'] == "Y" && $surveyinfo['tokenanswerspersistence'] == 'N') {$surveysummary2 .= $clang->gT("Participants can save partially finished surveys")."<br />\n";}
        if ($surveyinfo['emailnotificationto'] != '')
        {
            $surveysummary2 .= $clang->gT("Basic email notification is sent to:")." {$surveyinfo['emailnotificationto']}<br />\n";
        }
        if ($surveyinfo['emailresponseto'] != '')
        {
            $surveysummary2 .= $clang->gT("Detailed email notification with response data is sent to:")." {$surveyinfo['emailresponseto']}<br />\n";
        }

        if(bHasSurveyPermission($surveyid,'surveycontent','update'))
        {
            $surveysummary2 .= $clang->gT("Regenerate question codes:")
            . " [<a href='#' "
            . "onclick=\"if (confirm('".$clang->gT("Are you sure you want regenerate the question codes?","js")."')) { ".get2post(base_url()."?action=renumberquestions&amp;sid=$surveyid&amp;style=straight")."}\" "
            . ">".$clang->gT("Straight")."</a>] "
            . " [<a href='#' "
            . "onclick=\"if (confirm('".$clang->gT("Are you sure you want regenerate the question codes?","js")."')) { ".get2post(base_url()."?action=renumberquestions&amp;sid=$surveyid&amp;style=bygroup")."}\" "
            . ">".$clang->gT("By Group")."</a>]";
        }

        $dateformatdetails=getDateFormatData($this->session->userdata('dateformat'));
        if (trim($surveyinfo['startdate'])!= '')
        {
            $constructoritems = array($surveyinfo['startdate'] , "Y-m-d H:i:s");
            $this->load->library('Date_Time_Converter',$constructoritems);
            $datetimeobj = $this->date_time_converter; //new Date_Time_Converter($surveyinfo['startdate'] , "Y-m-d H:i:s");
            $data['startdate']=$datetimeobj->convert($dateformatdetails['phpdate'].' H:i');
        }
        else
        {
            $data['startdate']="-";
        }

        if (trim($surveyinfo['expires'])!= '')
        {
            $constructoritems = array($surveyinfo['expires'] , "Y-m-d H:i:s");
            $this->load->library('Date_Time_Converter',$constructoritems);
            $datetimeobj = $this->date_time_converter;
            //$datetimeobj = new Date_Time_Converter($surveyinfo['expires'] , "Y-m-d H:i:s");
            $data['expdate']=$datetimeobj->convert($dateformatdetails['phpdate'].' H:i');
        }
        else
        {
            $data['expdate']="-";
        }

        if (!$surveyinfo['language']) {$data['language']=getLanguageNameFromCode($currentadminlang,false);} else {$data['language']=getLanguageNameFromCode($surveyinfo['language'],false);}

        // get the rowspan of the Additionnal languages row
        // is at least 1 even if no additionnal language is present
        $additionnalLanguagesCount = count($aAdditionalLanguages);
        $first=true;
		$data['additionnalLanguages']="";
        if ($additionnalLanguagesCount == 0)
        {
                    $data['additionnalLanguages'] .= "<td align='left'>-</td>\n";
        }
        else
        {
            foreach ($aAdditionalLanguages as $langname)
            {
                if ($langname)
                {
                    if (!$first) {$data['additionnalLanguages'].= "<tr><td>&nbsp;</td>";}
                    $first=false;
                    $data['additionnalLanguages'] .= "<td align='left'>".getLanguageNameFromCode($langname,false)."</td></tr>\n";
                }
            }
        }
        if ($first) $data['additionnalLanguages'] .= "</tr>";

        if ($surveyinfo['surveyls_urldescription']==""){$surveyinfo['surveyls_urldescription']=htmlspecialchars($surveyinfo['surveyls_url']);}

        if ($surveyinfo['surveyls_url']!="")
        {
            $data['endurl'] = " <a target='_blank' href=\"".htmlspecialchars($surveyinfo['surveyls_url'])."\" title=\"".htmlspecialchars($surveyinfo['surveyls_url'])."\">{$surveyinfo['surveyls_urldescription']}</a>";
        }
        else
        {
            $data['endurl'] ="-";
        }

		$data['sumcount3']=$sumcount3;
		$data['sumcount2']=$sumcount2;

        if ($activated == "N")
        {
            $data['activatedlang'] = $clang->gT("No");
        }
        else
        {
            $data['activatedlang'] = $clang->gT("Yes");
        }

		$data['activated']=$activated;
        if ($activated == "Y")
        {
            $data['surveydb']="{{survey_".$surveyid."}}";
        }
 		$data['warnings']="";
        if ($activated == "N" && $sumcount3 == 0)
        {
            $data['warnings']= $clang->gT("Survey cannot be activated yet.")."<br />\n";
            if ($sumcount2 == 0 && bHasSurveyPermission($surveyid,'surveycontent','create'))
            {
                $data['warnings'] .= "<span class='statusentryhighlight'>[".$clang->gT("You need to add question groups")."]</span><br />";
            }
            if ($sumcount3 == 0 && bHasSurveyPermission($surveyid,'surveycontent','create'))
            {
               $data['warnings'] .= "<span class='statusentryhighlight'>[".$clang->gT("You need to add questions")."]</span><br />";
            }
        }
        $data['hints']=$surveysummary2;

        //return (array('column'=>array($columns_used,$hard_limit) , 'size' => array($length, $size_limit) ));

//        $data['tableusage'] = get_dbtableusage($surveyid);
// ToDo: Table usage is calculated on every menu display which is too slow with bug surveys.
// Needs to be moved to a database field and only updated if there are question/subquestions added/removed (it's currently also not functional due to the port)
//
        $data['tableusage'] = false;

        //$gid || $qid ||


        if ($action=="deactivate"|| $action=="activate" || $action=="surveysecurity" || $action=="editdefaultvalues" || $action == "editemailtemplates"
        || $action=="surveyrights" || $action=="addsurveysecurity" || $action=="addusergroupsurveysecurity"
        || $action=="setsurveysecurity" ||  $action=="setusergroupsurveysecurity" || $action=="delsurveysecurity"
        || $action=="editsurveysettings"|| $action=="editsurveylocalesettings" || $action=="updatesurveysettingsandeditlocalesettings" || $action=="addgroup" || $action=="importgroup"
        || $action=="ordergroups" || $action=="deletesurvey" || $action=="resetsurveylogic"
        || $action=="importsurveyresources" || $action=="translate"  || $action=="emailtemplates"
        || $action=="exportstructure" || $action=="quotas" || $action=="copysurvey" || $action=="viewgroup" || $action == "viewquestion") {$showstyle="style='display: none'";}
        if (!isset($showstyle)) {$showstyle="";}
        /**if ($gid) {$showstyle="style='display: none'";}
        if (!isset($showstyle)) {$showstyle="";} */
        $data['showstyle'] = $showstyle;
        $data['aAdditionalLanguages'] = $aAdditionalLanguages;
		$this->getController()->render("admin/survey/surveySummary_view",$data);

    }

	/**
	 * Browse Menu Bar
	 */
	function _browsemenubar($surveyid, $title='')
	{
	    //BROWSE MENU BAR
		$data['title'] = $title;
		$data['thissurvey'] = getSurveyInfo($surveyid);
		$data['imageurl'] = $this->config->item("imageurl");
		$data['clang'] = Yii::app()->lang;
		$data['surveyid'] = $surveyid;

		$tmp_survlangs = Survey::model()->findByPk($surveyid)->additionalLanguages;
        $baselang = Survey::model()->findByPk($surveyid)->language;
        $tmp_survlangs[] = $baselang;
        rsort($tmp_survlangs);
		$data['tmp_survlangs'] = $tmp_survlangs;

	    $this->getController()->render("admin/browse/browsemenubar_view", $data);
	}
}