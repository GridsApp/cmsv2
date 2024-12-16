import '../../../../../../resources/js/bootstrap.js';
import "./fa-kit.js";
import Functions from "./functions.js";



window.Functions = new Functions();



window.addEventListener('DOMContentLoaded' , function(){
   
});


// document.addEventListener("keydown", function(event) {
//     console.log(event);
// });



// const focusedElement = document.activeElement;




// document.addEventListener("keydown", function(event) {
   

//     let focusedElement = document.activeElement;



//     let languageElement = focusedElement.closest('.language-element');
    
//     if(languageElement == null){
//         return;
//     }
    
//     let langPicker = languageElement?.querySelector('.lang-picker');


    



//     let id = languageElement.id;


//     if(langPicker == null){
//         return;
//     }

//     let values = [];
//     let options = langPicker.querySelectorAll('option').forEach((option) => {
//         values.push(option.value);
//     });

//     let lastIndex = values.length - 1;



//     if (event.key === "ArrowRight" || event.keyCode === 39) {
       
//         if(langPicker.value == lastIndex){
//             langPicker.value = "0";  
//         }else{
//             langPicker.value = String(parseInt(langPicker.value) + 1);  
//         }

//         // language-changed

//         window.dispatchEvent(
//             new CustomEvent("language-changed-"+id, {
//                 detail: { languageIndex : langPicker.value}
//             })
//         );


      
       
//     }
    

//     if (event.key === "ArrowLeft" || event.keyCode === 37) {
       
//         if(langPicker.value == "0"){
//             langPicker.value = lastIndex;  
//         }else{
//             langPicker.value = String(parseInt(langPicker.value) - 1);  
//         }

//         window.dispatchEvent(
//             new CustomEvent("language-changed-"+id, {
//                 detail: { languageIndex : langPicker.value}
//             })
//         );

       
       

//     }

// });

