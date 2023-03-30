# Geo Chart automatic increment
Example of programmer Sergei Kozlov

This example emulates the automatic increment of views in increments of 2 weeks to a month.

### Work example
https://demo.sergeykozlov.ru/geo_chart/

## To use this framework

### Define API values.

$tm->setItemId('qwerty');
$tm->setCountShow(1234);

### Run loops like in this example
https://github.com/SergeyKozlov/geo_chart/blob/158bd627656e0a447e92e241af61d0a812447388/system/cm/tm_action/index.php

$tm->foreachTM($tm->getDate1start(), $tm->getDate1stop(), $tm->getRiseCountShow());
$tm->foreachTM($tm->getDate2start(), $tm->getDate2stop(), $tm->getFallCountShow());

### Displaying system messages
$log->getStaffMessage()

## all API of TM class

        $this->setLuft(5);
        $this->setEvolutionMidlRangeMin(100);
        $this->setEvolutionMidlRangeMax(200);
        $this->setEvolutionHightyRangeMin(200);
        $this->setEvolutionHightyRangeMax(600);
        $this->setEvolutionHightyChance(5);
        $this->setEvolutionCriterion1(100);
        $this->setRisePercentShow(55);
        $this->setFallPercentShow(45);
        $this->setRisePercentShowHighty(65);
        $this->setFallPercentShowHighty(35);
        $this->setLuftPercentShow(10);
        $this->setRiseDaysMin(10); // 10 days
        $this->setRiseDaysMax(14); // 2 week
        $this->setRiseDaysMinHighty(14); // 2 week
        $this->setRiseDaysMaxHighty(28); // 3 week
        $this->setFallDaysMin(10); // 1 week
        $this->setFallDaysMax(28); // 3 week
        $this->setFallDaysMinHighty(28); // 3 week
        $this->setFallDaysMaxHighty(42); // 6 week
        $this->setCountLuftPercentMin(50);
        $this->setCountLuftPercentMax(150);
        $this->setCountJustNow(1);
        $this->setUsa(5);
        $this->setEu(7);
        $this->setAsia(9);
        $this->log = new log();

## Result
![Screenshot 2023-03-21 at 13-51-22 Vide me](https://user-images.githubusercontent.com/1781376/226585349-d31084d2-2201-4c1e-a4b8-5a5ac8525803.png)
