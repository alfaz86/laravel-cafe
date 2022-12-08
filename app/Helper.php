<?php
namespace App;
use App\Models\Draft;
 
class Helper {
    public static function strToNumber($string) {
        return (int)preg_replace('/\D/', '', $string);
    }

    public static function formatRupiah($string): string
    {
        return "Rp ".number_format($string, 0, ',', '.');
    }

    public static function totalDraft()
    {
        return Draft::where('user_id', auth()->user()->id)->get()->count();
    }
}