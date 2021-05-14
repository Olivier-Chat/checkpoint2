<?php


namespace App\Service;

class ColorGenerator
{
    public function generateBackground(array $colors): string
    {
        $tempArray = [];
        $tempArray2 = [];
        foreach ($colors as $color) {
            $color = array_map('hexdec', $this->extractHexadecimalsFromColor($color));
            $tempArray [] = $color;
        }
        $averages = $this->calculateAvgInArrays($tempArray);
        $averagesHexa = array_map('dechex', $averages);
        foreach ($averagesHexa as $average) {
            if (strlen($average) < 2) {
                $tempArray2 []= "0" . $average;
            } else {
                $tempArray2 [] = $average;
            }
        }
        return "#" . implode("", $tempArray2);
    }
    public function extractHexadecimalsFromColor(string $color): array
    {
        $result  = [];
        $splitColor = str_split($color);
        $length = count($splitColor);
        for ($i = 1; $i < $length -1; $i += 2) {
            $result [] = $splitColor[$i] . $splitColor[$i+1];
        }
        return $result;
    }
    public function calculateAvgInArrays(array $colors): array
    {
        $sumOfRed = 0;
        $sumOfGreen = 0;
        $sumOfBlue = 0;

        foreach ($colors as $color) {
            $sumOfRed += $color[0];
            $sumOfGreen += $color[1];
            $sumOfBlue += $color[2];
        }
        $length = count($colors);
        return [round($sumOfRed/$length), round($sumOfGreen/$length), round($sumOfBlue/$length)];
    }
    public function invertColor(string $color): string
    {
        $tempColors = array_map(function (string $primary): int {
            return 255-hexdec($primary);
        }, $this->extractHexadecimalsFromColor($color));
        $tempColors = array_map('dechex', $tempColors);
        return '#' . implode("", $tempColors);
    }
}
