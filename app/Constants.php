<?php

namespace App;

class Constants
{
    public const IDENTIFIER_USE = ['usual', 'official', 'temp', 'secondary', 'old'];
    public const IDENTIFIER_USE_DISPLAY = [
        'usual' => 'Identifier yang direkomendasikan digunakan untuk interaksi dunia nyata',
        'official' => 'Identifier yang dianggap paling terpercaya. Terkadang juga dikenal sebagai "primer" dan "utama". Penentuan "resmi" bersifat subyektif dan panduan implementasi seringkali memberikan panduan tambahan untuk digunakan.',
        'temp' => 'Identifier sementara',
        'secondary' => 'Identifier yang ditugaskan dalam penggunaan sekunder ini berfungsi untuk mengidentifikasi objek dalam konteks relatif, tetapi tidak dapat secara konsisten ditugaskan ke objek yang sama lagi dalam konteks yang berbeda.',
        'old' => 'Id identifier sudah dianggap tidak valid, tetapi masih memungkinkan relevan untuk kebutuhan pencarian.'
    ];

    public const TELECOM_SYSTEM_SYSTEM = 'http://hl7.org/fhir/contact-point-system';
    public const TELECOM_SYSTEM_CODE = ['phone', 'fax', 'email', 'pager', 'url', 'sms', 'other'];
    public const TELECOM_SYSTEM_DISPLAY = ['phone' => 'Phone', 'fax' => 'Fax', 'email' => 'Email', 'pager' => 'Pager', 'url' => 'URL', 'sms' => 'SMS', 'other' => 'Other'];
    public const TELECOM_SYSTEM_DEFINITION = ["phone" => "The value is a telephone number used for voice calls. Use of full international numbers starting with + is recommended to enable automatic dialing support but not required.", "fax" => "The value is a fax machine. Use of full international numbers starting with + is recommended to enable automatic dialing support but not required.", "email" => "The value is an email address.", "pager" => "The value is a pager number. These may be local pager numbers that are only usable on a particular pager system.", "url" => "A contact that is not a phone, fax, pager or email address and is expressed as a URL. This is intended for various institutional or personal contacts including web sites, blogs, Skype, Twitter, Facebook, etc. Do not use for email addresses.", "sms" => "A contact that can be used for sending an sms message (e.g. mobile phones, some landlines).", "other" => 'A contact that is not a phone, fax, page or email address and is not expressible as a URL. E.g. Internal mail address. This SHOULD NOT be used for contacts that are expressible as a URL (e.g. Skype, Twitter, Facebook, etc.) Extensions may be used to distinguish "other" contact types.'];

    public const TELECOM_USE_SYSTEM = 'http://hl7.org/fhir/contact-point-use';
    public const TELECOM_USE_CODE = ['home', 'work', 'temp', 'old', 'mobile'];
    public const TELECOM_USE_DISPLAY = ['home' => 'Home', 'work' => 'Work', 'temp' => 'Temp', 'old' => 'Old', 'mobile' => 'Mobile'];
    public const TELECOM_USE_DEFINITION = ["home" => "A communication contact point at a home; attempted contacts for business purposes might intrude privacy and chances are one will contact family or other household members instead of the person one wishes to call. Typically used with urgent cases, or if no other contacts are available.", "work" => "An office contact point. First choice for business related contacts during business hours.", "temp" => "A temporary contact point. The period can provide more detailed information.", "old" => "This contact point is no longer in use (or was never correct, but retained for records).", "mobile" => "A telecommunication device that moves and stays with its owner. May have characteristics of all other use codes, suitable for urgent matters, not the first choice for routine business."];

    public const ADDRESS_USE_SYSTEM = 'http://hl7.org/fhir/address-use';
    public const ADDRESS_USE_CODE = ['home', 'work', 'temp', 'old', 'billing'];
    public const ADDRESS_USE_DISPLAY = ['home' => 'Home', 'work' => 'Work', 'temp' => 'Temporary', 'old' => 'Old / Incorrect', 'billing' => 'Billing'];
    public const ADDRESS_USE_DEFINITION = ["home" => "A communication address at a home.", "work" => "An office address. First choice for business related contacts during business hours.", "temp" => "A temporary address. The period can provide more detailed information.", "old" => "This address is no longer in use (or was never correct but retained for records).", "billing" => "An address to be used to send bills, invoices, receipts etc."];

    public const GENDER = ['male', 'female', 'other', 'unknown'];
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
