(function () {
    'use strict'
  
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
  
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
  
          form.classList.add('was-validated')
        }, false)
      })
  })()

//////////////////INCOMES///////////////////////////////////////////////////////////////////////

  var editOperationModal = document.getElementById('editCategoryIncome')
  editOperationModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget
  var oldName = button.getAttribute('data-bs-whatever')
  var modalBodyInput = editOperationModal.querySelector('.modal-body input')
  var modalBodyInputH = editOperationModal.querySelector('.modal-body-H input')
  modalBodyInput.value = oldName
  modalBodyInputH.value = oldName
 })


 var deleteOperationModal = document.getElementById('deleteCategoryIncome')
 deleteOperationModal.addEventListener('show.bs.modal', function (event) {
 var button = event.relatedTarget
 var oldName = button.getAttribute('data-bs-whatever')
 var modalBodyInputH = deleteOperationModal.querySelector('.modal-body-H input')
 var modalTitle = deleteOperationModal.querySelector('.text')
 modalBodyInputH.value = oldName
 modalTitle.textContent = oldName
})

//////////////////EXPENSES///////////////////////////////////////////////////////////////////////

var editOperationModalExp = document.getElementById('editCategoryExpense')
editOperationModalExp.addEventListener('show.bs.modal', function (event) {
var button = event.relatedTarget
var oldName = button.getAttribute('data-bs-whatever')
var modalBodyInput = editOperationModalExp.querySelector('.modal-body input')
var modalBodyInputH = editOperationModalExp.querySelector('.modal-body-H input')
modalBodyInput.value = oldName
modalBodyInputH.value = oldName
})


var deleteOperationModalExp = document.getElementById('deleteCategoryExpense')
deleteOperationModalExp.addEventListener('show.bs.modal', function (event) {
var button = event.relatedTarget
var oldName = button.getAttribute('data-bs-whatever')
var modalBodyInputH = deleteOperationModalExp.querySelector('.modal-body-H input')
var modalTitle = deleteOperationModalExp.querySelector('.text')
modalBodyInputH.value = oldName
modalTitle.textContent = oldName
})

//////////////////PAYMENT_METHODS///////////////////////////////////////////////////////////////////////

var editOperationModalPay = document.getElementById('editPaymentMethod')
editOperationModalPay.addEventListener('show.bs.modal', function (event) {
var button = event.relatedTarget
var oldName = button.getAttribute('data-bs-whatever')
var modalBodyInput = editOperationModalPay.querySelector('.modal-body input')
var modalBodyInputH = editOperationModalPay.querySelector('.modal-body-H input')
modalBodyInput.value = oldName
modalBodyInputH.value = oldName
})


var deleteOperationModalPay = document.getElementById('deletePaymentMethod')
deleteOperationModalPay.addEventListener('show.bs.modal', function (event) {
var button = event.relatedTarget
var oldName = button.getAttribute('data-bs-whatever')
var modalBodyInputH = deleteOperationModalPay.querySelector('.modal-body-H input')
var modalTitle = deleteOperationModalPay.querySelector('.text')
modalBodyInputH.value = oldName
modalTitle.textContent = oldName
})


//////////////////////Limit///////////////////////////
var editOperationModalLimit = document.getElementById('editLimit')
editOperationModalLimit.addEventListener('show.bs.modal', function (event) {
var button = event.relatedTarget
var oldName = button.getAttribute('data-bs-whatever')
var modalBodyInput = editOperationModalLimit.querySelector('.modal-body input')
var modalBodyInputH = editOperationModalLimit.querySelector('.modal-body-H input')
modalBodyInput.value = oldName
modalBodyInputH.value = oldName
})