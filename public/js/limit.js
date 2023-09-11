
// Const declarations.
const amountField = document.querySelector('#inputAmount');
const dateField = document.querySelector('#inputDate');
const categoryField = document.querySelector('#inputCategory');

const limitInfoBox = document.querySelector('#limit_info_box');
const limitValueBox = document.querySelector('#limit_value_box');
const limitLeftBox = document.querySelector('#limit_left_box');
const legendBox = document.querySelectorAll('.legend');

const limitInfo = document.createElement('p');
const limitValue = document.createElement('p');
const limitLeft = document.createElement('p');

// Rendering alert boxes.
const renderInfoBox = (field, limit) => {
    if (!!limit) {
        limitInfo.innerText = `${limit} zł`; //limit  
    } else {
        limitInfo.innerText = `-`; //nie ma limitu
    }

    field.appendChild(limitInfo);
}

const renderValueBox = (field, monthlyExpenses) => {
    if (!!monthlyExpenses) {
        limitValue.innerText = `${monthlyExpenses} zł`;   //dotychczas wydano
    } else {
        limitValue.innerText = `0 zł`; //nic nie wydano jeszcze
    }

    field.appendChild(limitValue);
}

const renderLeftBox = (field, limitInfoData, monthlyExpenses, amount) => {

    limitLeft.innerText = `${(monthlyExpenses - (- amount)).toFixed(2)} zł`; //wydatki+kwota wpisana  
    
    if (((monthlyExpenses - (- amount)) > limitInfoData) && limitInfoData)
        limitLeft.style.color='red';
    else
        limitLeft.style.color='white';
    
    field.appendChild(limitLeft);
}


// Events logic.
const eventsAction = async (category, date, amount) => {
    if (category && date && amount && (amount>0)) {

        const limitInfoData = await getLimitForCategory(category);
        const monthlyExpenses = await getMonthlyExpenses(category, date);
        
        if (limitInfoData) {

            legendBox.forEach(element => {
            element.innerHTML = '<span class="col-4 text-center fs-6">Limit:</span><span class="col-4 text-center fs-6">Dotychczas wydano:</span><span class="col-4 text-center fs-6">Wydatki + wpisana kwota:</span>';
            });
            renderInfoBox(limitInfoBox, limitInfoData);
            renderValueBox(limitValueBox, monthlyExpenses);
            renderLeftBox(limitLeftBox, limitInfoData, monthlyExpenses, amount);
        }
        else 
        {
            legendBox.forEach(element => {element.innerHTML = '';});
            limitInfoBox.innerText = ``;
            limitValueBox.innerText = ``;
            limitLeftBox.innerText = ``;
        }
    }
    else 
    {
        legendBox.forEach(element => {element.innerHTML = '';});
        limitInfoBox.innerText = ``;
        limitValueBox.innerText = ``;
        limitLeftBox.innerText = ``;
    }
}

// Async fetch funtcions.
const getLimitForCategory = async (category) => {
    try {
        const response = await fetch(`../api/limit/${category}`);
        const data = await response.json();
        return data;
    } catch (e) {
        console.log('ERROR', e);
    }
}

const getMonthlyExpenses = async (category, date) => {
    try {
        const response = await fetch(`../api/limitSum/${category}/${date}`);
        const data = await response.json();
        if (data)
            return data;
        else
            return 0;
        
    } catch (e) {
        console.log('ERROR', e);
    }
}


// Event listeners.
amountField.addEventListener('change', async () => {
    const category = categoryField.options[categoryField.selectedIndex].value;
    const date = dateField.value;
    const amount = amountField.value;
    eventsAction(category, date, amount);
})

dateField.addEventListener('change', async () => {
    const category = categoryField.options[categoryField.selectedIndex].value;
    const date = dateField.value;
    const amount = amountField.value;
    if (category != "cat0")
        eventsAction(category, date, amount);
})

categoryField.addEventListener('change', async () => {
    const category = categoryField.options[categoryField.selectedIndex].value;
    const date = dateField.value;
    const amount = amountField.value;
    eventsAction(category, date, amount);
})

///////////////////Edit Category//////////////////

const toggle = document.getElementById('toggle');
const collapse = document.getElementById('collapseLimit');

toggle.addEventListener('change', function() {
  if (toggle.checked) {
    collapse.style.display = 'block';
  } else {
    collapse.style.display = 'none';
  }
});