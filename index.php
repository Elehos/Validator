<?php

require __DIR__.'/vendor/autoload.php';

//Validator Boolean
echo \Web1\Validator\ValidatorBoolean::validatorBoolean(true);

//Validator Integer
echo \Web1\Validator\ValidatorInt::intEqual(5,5); //Equal

echo \Web1\Validator\ValidatorInt::intInf(5,6); //Inférieur

echo \Web1\Validator\ValidatorInt::intSup(10,4); //Supérieur

echo \Web1\Validator\ValidatorInt::intBetween(5,1,100); //Compris entre X etX

echo \Web1\Validator\ValidatorInt::intNegative(-5); //Négatif

echo \Web1\Validator\ValidatorInt::intPositive(7); //Positif

//Validator String
echo \Web1\Validator\ValidatorString::stringLenEqual(5,"hello");

echo \Web1\Validator\ValidatorString::stringLenInf(8,"hello");

echo \Web1\Validator\ValidatorString::stringLenSup(3,"hello");

echo \Web1\Validator\ValidatorString::stringLenBetween("hello",1,100);

echo \Web1\Validator\ValidatorString::stringNoSpaceStartEnd(" hello ");


