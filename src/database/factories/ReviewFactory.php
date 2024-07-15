<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // ユーザーIDを3、4、5の中からランダムに選択
        $userIds = [3, 4, 5, 6, 7, 8, 9, 10];

        // ショップIDを1から20の中からランダムに選択
        $shopIds = range(1, 20);

        $userId = $this->faker->randomElement($userIds);
        $shopId = $this->faker->randomElement($shopIds);

        // 同じユーザーが同じ店舗に対して既にレビューを持っていないことを確認
        // ランダムに選んだ組み合わせが既に存在する場合、新しい組み合わせを探す
        $attempts = 0;
        while (Review::where('user_id', $userId)->where('shop_id', $shopId)->exists() && $attempts < 10) {
            $userId = $this->faker->randomElement($userIds);
            $shopId = $this->faker->randomElement($shopIds);
            $attempts++;
        }

        return [
            'user_id' => $userId,
            'shop_id' => $shopId,
            'rating' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->realText(400),
        ];
    }
}
