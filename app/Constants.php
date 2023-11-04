<?php

namespace App;

class Constants
{
    public const IDENTIFIER_USE = ['usual', 'official', 'temp', 'secondary', 'old'];

    public const COMPARATOR = ['<', '<=', '>=', '>'];
    public const PERIOD_UNIT = ['s', 'min', 'h', 'd', 'wk', 'mo', 'a'];
    public const DAY_OF_WEEK = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    public const TIMING_EVENT_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-TimingEvent';
    public const TIMING_EVENT_CODE = ['AC', 'ACD', 'ACM', 'ACV', 'C', 'CD', 'CM', 'CV', 'HS', 'IC', 'ICD', 'ICM', 'ICV', 'PC', 'PCD', 'PCM', 'PCV', 'WAKE'];
    public const TIMING_EVENT_DISPLAY = ['AC' => 'AC', 'ACD' => 'ACD', 'ACM' => 'ACM', 'ACV' => 'ACV', 'C' => 'C', 'CD' => 'CD', 'CM' => 'CM', 'CV' => 'CV', 'HS' => 'HS', 'IC' => 'IC', 'ICD' => 'ICD', 'ICM' => 'ICM', 'ICV' => 'ICV', 'PC' => 'PC', 'PCD' => 'PCD', 'PCM' => 'PCM', 'PCV' => 'PCV', 'WAKE' => 'WAKE'];
    public const TIMING_EVENT_DEFINITION = ["AC" => "before meal (from lat. ante cibus)", "ACD" => "before lunch (from lat. ante cibus diurnus)", "ACM" => "before breakfast (from lat. ante cibus matutinus)", "ACV" => "before dinner (from lat. ante cibus vespertinus)", "C" => "Description: meal (from lat. ante cibus)", "CD" => "Description: lunch (from lat. cibus diurnus)", "CM" => "Description: breakfast (from lat. cibus matutinus)", "CV" => "Description: dinner (from lat. cibus vespertinus)", "HS" => "Description: Prior to beginning a regular period of extended sleep (this would exclude naps). Note that this might occur at different times of day depending on a person's regular sleep schedule.", "IC" => "between meals (from lat. inter cibus)", "ICD" => "between lunch and dinner", "ICM" => "between breakfast and lunch", "ICV" => "between dinner and the hour of sleep", "PC" => "after meal (from lat. post cibus)", "PCD" => "after lunch (from lat. post cibus diurnus)", "PCM" => "after breakfast (from lat. post cibus matutinus)", "PCV" => "after dinner (from lat. post cibus vespertinus)", "WAKE" => "Description: Upon waking up from a regular period of sleep, in order to start regular activities (this would exclude waking up from a nap or temporarily waking up during a period of sleep) Usage Notes: e.g. Take pulse rate on waking in management of thyrotoxicosis. Take BP on waking in management of hypertension Take basal body temperature on waking in establishing date of ovulation"];

    public const TIMING_ABBREVIATION_SYSTEM = 'http://hl7.org/fhir/ValueSet/timing-abbreviation';
    public const TIMING_ABBREVIATION_CODE = ['BID', 'TID', 'QID', 'AM', 'PM', 'QD', 'QOD', 'Q1H', 'Q2H', 'Q3H', 'Q4H', 'Q6H', 'Q8H', 'BED', 'WK', 'MO'];
    public const TIMING_ABBREVIATION_DISPLAY = ['BID' => 'BID', 'TID' => 'TID', 'QID' => 'QID', 'AM' => 'AM', 'PM' => 'PM', 'QD' => 'QD', 'QOD' => 'QOD', 'Q1H' => 'every hour', 'Q2H' => 'every 2 hours', 'Q3H' => 'every 3 hours', 'Q4H' => 'Q4H', 'Q6H' => 'Q6H', 'Q8H' => 'every 8 hours', 'BED' => 'at bedtime', 'WK' => 'weekly', 'MO' => 'monthly'];
}
