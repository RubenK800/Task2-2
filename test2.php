<?php
$alphabetArray  = array_merge(range('a','z'), range('A','Z'));
$integerArray = range(0, 9);
$twoInOneArray = array_merge($alphabetArray,$integerArray);

//without making JSON we can't send these variables to javascript
$alphabetArrayToJson = json_encode($alphabetArray);
$integerArrayToJson = json_encode($integerArray);
$twoInOneArrayToJson = json_encode($twoInOneArray);

//for testing the desired look
//echo "<form onsubmit='event.preventDefault();' style = 'width:180px; height: 225px; border: 1px solid black'>";
//    echo "<div style='margin: 10px'>";
//        echo "<input type='text' id='line-length' style = 'width: 160px; height: 25px' placeholder='տողի երկարությունը'><br>";
//        echo "<p>Տողի մեջ ներառել</p>";
//        echo "<select id ='select-list' style ='width: 115px; height: 25px' required>";
//            echo "<option value='numbers'>Թվեր</option>";
//            echo "<option value='letters'>Տառեր</option>";
//            echo "<option value='numbers-and-letters'>Թվեր և տառեր</option>";
//        echo "</select></br>";
//        echo "<input id = 'generate-btn' type='submit' value='գեներացնել' style ='width: 90px; height: 25px; margin-top: 70px'>";
//    echo "</div>";
//echo "</form>";

//now the program
$html = new DOMDocument('1.0','iso-8859-1');
$html-> formatOutput = true;

//createElements
$form = $html->createElement('form');
$div = $html->createElement('div');
$input = $html->createElement('input');
$br = $html->createElement('br');
$p = $html->createElement('p');
$select = $html->createElement('select');
$optionNumbers = $html->createElement('option');
$optionLetters = $html->createElement('option');
$optionNumbersAndLetters = $html->createElement('option');
$inputGenerateButton = $html->createElement('input');

//setElementsAttributes
setElementsAttributes($form, $div, $input, $select, $optionNumbers, $optionLetters, $optionNumbersAndLetters,
    $inputGenerateButton);

//setElementsTextContents
setElementsTextContents($p, $optionNumbers, $optionLetters, $optionNumbersAndLetters);

//appendElementsToHTML
appendElementsToHTML($form, $div, $input, $br, $p, $select, $optionNumbers, $optionLetters, $optionNumbersAndLetters,
    $inputGenerateButton);

$html->appendChild($form);

//without this code you will not be able to access by js and will always get "null" as result
echo html_entity_decode($html->saveHTML());

echo "<script type='text/javascript'> 
      let form = document.getElementsByTagName('form');
      let enteredLineLength = document.getElementById('line-length'); 
      let selectList = document.getElementById('select-list');
      let generateButton = document.getElementById('generate-btn');
      //without parsing from JSON we can't use variables from php in our javascript code
      let alphabetArray = JSON.parse('$alphabetArrayToJson');
      let integerArray   = JSON.parse('$integerArrayToJson');
      let twoInOneArray = JSON.parse('$twoInOneArrayToJson');
      
      let br = document.createElement('br');
      let line = document.createElement('p');
      let line2 = document.createElement('p');
      let line3 = document.createElement('p');
      generateButton.addEventListener('click', function(){
          if (selectList.value === 'numbers' && enteredLineLength.value!==''){
              let str = ''; 
              let randomElementStart = 0;
              let randomElementEnd = integerArray.length-1;
              //alert('integerArray.length ' + integerArray.length);
              for (let x = 0; x<enteredLineLength.value; ++x){
                  str = str+integerArray[randomInteger(randomElementStart,randomElementEnd)];
              } 
              
              line.textContent = str;
              
              form[0].appendChild(br);
              form[0].appendChild(line);
              
              //alert('line = ' + line.textContent);
              line2.hidden = true;
              line3.hidden = true;
          }else if (selectList.value === 'letters' && enteredLineLength.value!==''){
              let str = ''; 
              let randomElementStart = 0;
              let randomElementEnd = alphabetArray.length-1;
              //alert('integerArray.length ' + integerArray.length);
              for (let x = 0; x<enteredLineLength.value; ++x){
                  str = str+alphabetArray[randomInteger(randomElementStart,randomElementEnd)];
              } 
              
              line.textContent = str;
              form[0].appendChild(br);
              form[0].appendChild(line);
              
              line2.hidden = true;
              line3.hidden = true;
          }else if (selectList.value === 'numbers-and-letters' && enteredLineLength.value!==''){
              let str = ''; 
              let randomElementStart = 0;
              let randomElementEnd = twoInOneArray.length-1;
              //alert('twoInOneArray.length ' + twoInOneArray.length);
              for (let x = 0; x<enteredLineLength.value; ++x){
                  str = str+twoInOneArray[randomInteger(randomElementStart,randomElementEnd)];
              } 
              
              line.textContent = str;

              line2.textContent = str.replace(/[0-9]/g, '');

              line3.textContent = str.replace(/\D/g,'');
              
              form[0].appendChild(br);
              form[0].appendChild(line);
              form[0].appendChild(line2);
              form[0].appendChild(line3);
              line2.hidden = false;
              line3.hidden = false;
          }
	  });
      
      function randomInteger(min, max) {
          return Math.floor(Math.random() * (max - min + 1)) + min;
      }
      //alert('JavaScript is awesome and '+ obj); 
      </script>";


function setElementsAttributes($form, $div, $input, $select, $optionNumbers, $optionLetters, $optionNumbersAndLetters,
                               $inputGenerateButton) {
    setElementsStyleAttributes($form, $div, $input, $select, $inputGenerateButton);
    setElementsIdAttributes($input, $select, $inputGenerateButton);
    setFormElementsValueAttributes($optionNumbers, $optionLetters, $inputGenerateButton, $optionNumbersAndLetters);
    setFormElementsTypeAttributes($input, $inputGenerateButton);
    setFormElementOnSubmitAttribute($form);
    setFormInputElementPlaceHolderAttribute($input);
}

function setElementsStyleAttributes($form, $div, $input, $select, $inputGenerateButton){
    $form->setAttribute('style','width:180px; height: 225px; border: 1px solid black');
    $div->setAttribute('style','margin: 10px');
    $input->setAttribute('style', 'width: 160px; height: 25px');
    $select ->setAttribute('style','width: 115px; height: 25px');
    $inputGenerateButton -> setAttribute('style','width: 90px; height: 25px; margin-top: 70px');
}

function setElementsIdAttributes($input, $select, $inputGenerateButton){
    $input->setAttribute('id', 'line-length');
    $select ->setAttribute('id', 'select-list');
    $inputGenerateButton -> setAttribute('id','generate-btn');
}

function setFormElementsValueAttributes($optionNumbers, $optionLetters, $inputGenerateButton, $optionNumbersAndLetters){
    $optionNumbers->setAttribute('value','numbers');
    $optionLetters ->setAttribute('value','letters');
    $optionNumbersAndLetters ->setAttribute('value','numbers-and-letters');
    $inputGenerateButton -> setAttribute('value','գեներացնել');
}

function setFormElementsTypeAttributes($input, $inputGenerateButton){
    $input->setAttribute('type', 'text');
    $inputGenerateButton -> setAttribute('type','submit');
}

function setFormElementOnSubmitAttribute($form){
    $form->setAttribute('onsubmit','event.preventDefault()');
}

function setFormInputElementPlaceHolderAttribute($input){
    $input->setAttribute('placeholder','տողի երկարությունը');
}

function setElementsTextContents($p, $optionNumbers, $optionLetters, $optionNumbersAndLetters){
    $p->textContent = 'Տողի մեջ ներառել';
    $optionNumbers->textContent = 'Թվեր';
    $optionLetters ->textContent = 'Տառեր';
    $optionNumbersAndLetters ->textContent = 'Թվեր և տառեր';
}

function appendElementsToHTML($form, $div, $input, $br, $p, $select, $optionNumbers, $optionLetters,
                              $optionNumbersAndLetters, $inputGenerateButton){
    $form->appendChild($div);
    $div->appendChild($input);
    $input->appendChild($br);
    $div->appendChild($p);
    $div -> appendChild($select);
    $select ->appendChild($optionNumbers);
    $select ->appendChild($optionLetters);
    $select ->appendChild($optionNumbersAndLetters);
    $select ->appendChild($br);
    $div ->appendChild($inputGenerateButton);
}


