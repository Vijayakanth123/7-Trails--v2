
let rowCount = 1;

let wins = parseInt(document.getElementById('wins').innerText) || 0;
let losses = parseInt(document.getElementById('losses').innerText) || 0;
let streak = parseInt(document.getElementById('streak').innerText) || 0;

console.log("Game loaded with Stats:", wins, losses, streak);

let topStreak =  0;

let targetDigits = generateTargetDigits();

function syncStats(result) {
    // result should be 'win' or 'loss'
    const formData = new FormData();
    formData.append('result', result);

    fetch('game.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Database Sync:", data);
    })
    .catch(error => console.error('Error syncing stats:', error));
}

// Generates 3 numbers to match and returns the array
function generateTargetDigits() {
    let digits = [];
    while (digits.length < 3) {
        let d = Math.floor(Math.random() * 9) + 1;
        if (!digits.includes(d)) digits.push(d);
    }
    console.log("Target Digits:", digits);
    return digits;
}

//updates screen stats
function updateStats() {
    document.getElementById('wins').innerText = wins;
    document.getElementById('losses').innerText = losses;
    document.getElementById('streak').innerText = streak;
}

//THE BIG PROCESS
let gameend = false;
function handleSubmit() {
    const rows = document.querySelectorAll('.input-row');
    const lastRow = rows[rows.length - 1];
    const inputs = lastRow.querySelectorAll('.input-group input');
    const indicators = lastRow.querySelectorAll('.color-indicator');

    let values = [];
    let valid = true;
    inputs.forEach(input => {
        const val = parseInt(input.value);
        if (isNaN(val) || val < 1 || val > 9) valid = false;
        values.push(val);
    });

    if (new Set(values).size !== values.length) valid = false;

    if (!valid) {
        showwrongsubmit();      //have to define this shit to give a pop up
        inputs.forEach(input => input.value = "");
        return;
    }

    let colorMap = values.map((val, i) => {
        if (val === targetDigits[i]) return { color: 'var(--green-color)', type: 0 };
        else if (targetDigits.includes(val)) return { color: 'orange', type: 1 };
        else return { color: 'var(--red-color)', type: 2 };
    });

    colorMap.sort((a, b) => a.type - b.type);

    colorMap.forEach((entry, i) => {
        indicators[i].style.visibility = 'visible';
        indicators[i].style.backgroundColor = entry.color;
    });

    inputs.forEach(input => input.disabled = true);

    //after win effects
    if (values.every((val, idx) => val === targetDigits[idx])) { 
        wins++;
        streak++;
        gameend = true;
        topStreak = Math.max(topStreak, streak);
        updateStats();
        syncStats('win');
        document.getElementsByClassName('submit-btn')[0].disabled=true;
        alert("YOU WON\nThe Number : " + targetDigits.join(" "));
        //new
        resetGame()
        return;
    } else {
        if (rowCount === 7) {
            alert("You Lose! The number was: " + targetDigits.join(" "));
            gameend = true
            losses++;
            streak = 0;
            updateStats();
            syncStats('loss');
            document.getElementsByClassName('submit-btn')[0].disabled=true;
            return;
        }
    }

    

    if (rowCount < 7) {
        addNewRow();
        rowCount++;
    }
}

function addNewRow() {
    const container = document.getElementsByClassName("inputs-container")[0];
    const newRow = document.createElement("div");
    newRow.className = "input-row";
    newRow.innerHTML = `
        <div class="input-group">
            <input type="number" min="1" max="9">
            <input type="number" min="1" max="9">
            <input type="number" min="1" max="9">
        </div>
        <div class="color-indicators">
            <div class="color-indicator"></div>
            <div class="color-indicator"></div>
            <div class="color-indicator"></div>
        </div>
    `;
    container.append(newRow);
}

function resetGame() {
    document.getElementsByClassName('submit-btn')[0].disabled=false;
    const allRows = document.querySelectorAll('.input-row');
    allRows.forEach((row, i) => {
        if (i === 0) {
            const inputs = row.querySelectorAll('input');
            const indicators = row.querySelectorAll('.color-indicator');
            inputs.forEach(input => {
                input.disabled = false;
                input.value = "";
            });
            indicators.forEach( box =>{
                box.style.backgroundColor = "hsl(0,0%,25%)";
            });
            // indicators.forEach(ind => ind.style.visibility = 'hidden');
        } else {
            row.remove();
        }
    });
    rowCount = 1;
    targetDigits = generateTargetDigits();
    updateStats();
}

function handleresetgame(){
    if(document.getElementsByClassName('input-row').length > 1 && gameend==false){
        losses++;
        streak = 0;
        console.log("start loss sync");
        syncStats('loss');
        console.log("end loss sync");
        updateStats();
    }
    resetGame();
}

