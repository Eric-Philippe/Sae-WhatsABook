<?php

namespace App\Utils;

use App\Repository\MemberRepository;
use Symfony\Component\HttpFoundation\Request;

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

    public static function getEmailFromJWTToken(Request $request) {
        $token = $request->headers->get('Authorization');
        $tokenParts = explode(".", $token);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        return $jwtPayload->email;
    }

    public static function getMemberFromRequest(Request $request, MemberRepository $memberRepository) {
        $email = self::getEmailFromJWTToken($request);
        return $memberRepository->findOneBy(['email' => $email]);
    }
}