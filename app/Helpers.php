<?php

function getName($resource)
{
    $jsonData = json_decode($resource, true);

    if (isset($jsonData['name']) && !empty($jsonData['name'])) {
        return $jsonData['name'];
    }

    return null;
}

function parseName($nameData)
{
    if ($nameData === null) {
        return null;
    }

    foreach ($nameData as $name) {

        $displayName = '';

        if (isset($name['text']) && !empty($name['text'])) {
            $displayName = $name['text'];
        } else {
            $nameParts = [];

            if (isset($name['prefix']) && !empty($name['prefix'])) {
                $nameParts[] = implode(' ', $name['prefix']);
            }

            $givenName = isset($name['given']) ? implode(' ', $name['given']) : '';
            $familyName = isset($name['family']) ? $name['family'] : '';

            if (!empty($givenName)) {
                $nameParts[] = $givenName;
            }

            if (!empty($familyName)) {
                $nameParts[] = $familyName;
            }

            if (isset($name['suffix']) && !empty($name['suffix'])) {
                $nameParts[] = implode(' ', $name['suffix']);
            }

            $displayName = implode(' ', $nameParts);
        }

        if (isset($name['period']) && isset($name['period']['end'])) {
            $endDate = new DateTime($name['period']['end']);
            $currentDate = new DateTime();
            if ($endDate > $currentDate) {
                $displayName .= ' (Active)';
            }
        }

        return $displayName;
    }

    return null;
}
