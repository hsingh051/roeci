<?php

$election_date = '2016-12-23';

$first_evm = "-11 day";
$second_evm = "-1 day";

$first_staff = "-11 day";
$second_staff = "-5 day";
$third_staff = "-1 day";


$nomination_start = "-25 day";
$nomination_scrutiny = "-13 day";
$nomination_withdrawal = "2017-01-21";
$nomination_finallist = "-11 day";

$poll_arrangements = "-10 day";

$election_material = "-1 day";

$aws_s3_images_url = "https://s3.amazonaws.com/eci360-images/";
$aws_s3_files_url = "https://s3.amazonaws.com/eci360-files/";


// Config::get('constants.FIRST_RANDOMIZATION_DATE')
return [
	'ELECTION_DATE' => $election_date,
	'FIRST_RANDOMIZATION_STAFF_DATE' => date("Y-m-d",strtotime($first_staff,strtotime($election_date))),
	'SECOND_RANDOMIZATION_STAFF_DATE' => date("Y-m-d",strtotime($second_staff,strtotime($election_date))),
	'THIRD_RANDOMIZATION_STAFF_DATE' => date("Y-m-d",strtotime($third_staff,strtotime($election_date))),
	'FIRST_RANDOMIZATION_EVM_DATE' => date("Y-m-d",strtotime($first_evm,strtotime($election_date))),
	'SECOND_RANDOMIZATION_EVM_DATE' => date("Y-m-d",strtotime($second_evm,strtotime($election_date))),
	'NOMINATION_CANDIDATE_START_DATE' => date("Y-m-d",strtotime($nomination_start,strtotime($election_date))),
	'SCRUTINY_CANDIDATE_START_DATE' => date("Y-m-d",strtotime($nomination_scrutiny,strtotime($election_date))),
	'WIRHDRAWAL_CANDIDATE_START_DATE' => $nomination_withdrawal,
	'FINALLIST_NOMINATION_CANDIDATE_DATE' => date("Y-m-d",strtotime($nomination_finallist,strtotime($election_date))),
	'POLL_ARRANGEMENTS_DATE' => date("Y-m-d",strtotime($poll_arrangements,strtotime($election_date))),
	'ELECTION_MATERIAL_DATE' => date("Y-m-d",strtotime($election_material,strtotime($election_date))),
	'AWS_IMAGES_URL' => $aws_s3_images_url,
	'AWS_FILES_URL' => $aws_s3_files_url,
];
