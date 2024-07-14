<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function createShopManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $shopManager = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $shopManager->assignRole('shop_manager');

        return redirect()->route('admin.dashboard')->with('status', '店舗管理者を作成しました');
    }

    public function importCsv(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        // Process the CSV file
        $file = $request->file('csv_file');
        $filePath = $file->getPathname();

        $csv = array_map('str_getcsv', file($filePath));
        $header = array_shift($csv); // Get the header row

        // Validate header fields
        $requiredFields = ['店舗名', '地域', 'ジャンル', '店舗概要', '画像URL'];

        foreach ($requiredFields as $field) {
            if (!in_array($field, $header)) {
                return back()->withErrors(['message' => 'CSVファイルのヘッダーが正しくありません。必要なフィールドを含めてください。']);
            }
        }

        // Process each row in the CSV file
        foreach ($csv as $row) {
            $areaName = $row[array_search('地域', $header)];
            $genreName = $row[array_search('ジャンル', $header)];

            $area = DB::table('areas')->where('name', $areaName)->first();
            $genre = DB::table('genres')->where('name', $genreName)->first();

            if (!$area || !$genre) {
                return back()->withErrors(['message' => 'エリアまたはジャンルがデータベースに存在しません。']);
            }

            // Validate image URL extension
            $photoUrl = $row[array_search('画像URL', $header)];
            $extension = pathinfo($photoUrl, PATHINFO_EXTENSION);

            if (!in_array($extension, ['jpeg', 'png'])) {
                return back()->withErrors(['csv_errors' => '画像URLの拡張子はjpeg、pngのみ対応しています。']);
            }

            $shopName = $row[array_search('店舗名', $header)];
            if (mb_strlen($shopName) > 50) {
                return back()->withErrors(['csv_errors' => '店舗名は50文字以内で指定してください。']);
            }

            $info = $row[array_search('店舗概要', $header)];
            if (mb_strlen($info) > 400) {
                return back()->withErrors(['csv_errors' => '店舗概要は400文字以内で指定してください。']);
            }

            // Create a new shop record
            Shop::create([
                'shop_name' => $row[array_search('店舗名', $header)],
                'area_id' => $area->id,
                'genre_id' => $genre->id,
                'info' => $row[array_search('店舗概要', $header)],
                'photo_url' => $row[array_search('画像URL', $header)],
            ]);
        }

        return redirect()->route('admin.dashboard')->with('status', 'CSVファイルが正常にインポートされました。');
        }
}