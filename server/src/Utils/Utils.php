<?php

class Utils {
    public static function generateUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                       random_int(0, 0xffff), random_int(0, 0xffff),
                       random_int(0, 0xffff),
                       random_int(0, 0x0fff) | 0x4000,
                       random_int(0, 0x3fff) | 0x8000,
                       random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff));
    }

    public static function generateUniqueUUIDs(?int $count = 10) {
        $uuids = [];
        for ($i = 0; $i < $count; $i++) {
            $uuid = self::generateUUID();
            while (in_array($uuid, $uuids)) {
                $uuid = self::generateUUID();
            }
            $uuids[] = $uuid;
        }
        return $uuids;
    }	
}