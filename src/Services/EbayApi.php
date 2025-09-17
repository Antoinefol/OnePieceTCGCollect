<?php
namespace OnePieceTCGCollect\src\Services;

class EbayApi
{
    private static string $endpoint = "https://api.ebay.com/buy/browse/v1/item_summary/search";
    private static string $token = "v^1.1#i^1#I^3#f^0#p^3#r^0#t^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"; // ⚠️ ton token OAuth valide

    public static function getLastSoldPrice(string $version , string $number , string $extension): ?array
    {
        $queries = [
            $number . " " . $version . " " . $extension . " japanese",
            $number . " " . $version . " " . $extension
        ];

        foreach ($queries as $index => $query) {
            $params = [
                'q' => $query,
                'limit' => 1,
                'sort' => 'price'
            ];

            $url = self::$endpoint . '?' . http_build_query($params);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . self::$token,
                "Content-Type: application/json",
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            if (!$response) continue;

            $data = json_decode($response, true);

            if (!empty($data['itemSummaries'][0]['price']['value'])) {
                $price = (float) $data['itemSummaries'][0]['price']['value'];
                $urlAnnonce = $data['itemSummaries'][0]['itemWebUrl'] ?? '';

                $note = ($index === 1) ? "(autre langue)" : "";

                return [
                    'price' => $price,
                    'url' => $urlAnnonce,
                    'note' => $note
                ];
            }
        }

        return null;
    }
}
