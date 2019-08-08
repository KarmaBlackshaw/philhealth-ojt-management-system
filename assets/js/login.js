// $(document).ready(function() {
//     $('#formLogin').submit(function(e) {
//         e.preventDefault();
//         var data = $('#formLogin').serializeArray();
//         $.ajax({
//             url: 'controllers/LoginsController.php',
//             data: data,
//             method: 'POST',
//             success: function(data) {
//                 var data = JSON.parse(data);
//                 var bool = data.bool;
//                 var alert = data.alert;
//                 var message = data.message;
//                 if (bool == true) {} else {
//                     if (alert == 'warning') {
//                         alertWarning(message);
//                     } else if (alert == 'danger') {
//                         alertDanger(message);
//                     } else {
//                         console.log(data);
//                     }
//                 }
//             }
//         });
//     });
// });