var wordCount = 0;
var timerInterval;
var wordsPerSecond;
var isFirst = true;

document.addEventListener("keyup", (event) => {
    if (event.key === "Shift" || event.key === "Control") return;
    console.log(event);
    if (isFirst) {
        isFirst = false;
        startWordCounter();
    }
    var charList = document.getElementsByClassName("textCharacter");
    const activeID = getActiveCharID(charList);
    const currentChar = charList[activeID];
    const previousChar = getPreviousChar(charList, activeID);
    const nextChar = getNextChar(charList, activeID);
    if (currentChar === undefined) {
        return;
    }
    if (event.key === "Backspace") {
        moveBack(event, currentChar, previousChar);
    } else {
        moveForward(event, currentChar, nextChar);
    }
});

const moveBack = (event, currentChar, previousChar) => {
    //currentForamting
    currentChar.className += "upcoming";
    currentChar.className = currentChar.className.replace("active", "");
    currentChar.className = currentChar.className.replace(/\s\s+/g, " ");

    //previousForamting
    previousChar.className += " active";
    previousChar.className = previousChar.className.replace(/\s\s+/g, " ");
};
const moveForward = (event, currentChar, nextChar) => {
    const correct = event.key === currentChar.textContent;
    var cIsCorrect = hasAndRemoveClass(currentChar, "correct");
    var cIsFixed = hasAndRemoveClass(currentChar, "fixed");
    var cIsWrong = hasAndRemoveClass(currentChar, "wrong");
    if (correct && (cIsWrong || cIsFixed)) {
        currentChar.className += " fixed";
    } else if (correct) {
        currentChar.className += " correct";
    } else {
        currentChar.className += " wrong";
    }
    currentChar.style.animation = "0.05s pressDownKey ease-in-out forwards";

    //currentForamting

    currentChar.className = currentChar.className.replace("active", "");
    currentChar.className = currentChar.className.replace(/\s\s+/g, " ");

    if (nextChar == undefined) {
        endCalculations();
        return;
    }

    //nextFormating
    nextChar.className = nextChar.className.replace("upcoming", "");
    nextChar.className += " active";

    nextChar.className = nextChar.className.replace(/\s\s+/g, " ");
};

const endCalculations = () => {
    clearInterval(timerInterval);
    wordsPerMinute = Math.round(wordsPerSecond * 60 * 100) / 100;
    document.getElementById("wordsPerMinute").textContent =
        wordsPerMinute + " w/min";
    var wrongCount = 0;
    var correctCount = 0;
    var fixedCount = 0;
    var charList = document.getElementsByClassName("textCharacter");
    itemCount = charList.length;
    for (const item of charList) {
        var cIsCorrect = hasAndRemoveClass(item, "correct", false);
        var cIsFixed = hasAndRemoveClass(item, "fixed", false);
        var cIsWrong = hasAndRemoveClass(item, "wrong", false);
        if (cIsCorrect) correctCount += 1;
        if (cIsCorrect) console.log(correctCount + fixedCount);
        if (cIsFixed) fixedCount += 1;
        if (cIsWrong) wrongCount += 1;
    }
    accuracy =
        Math.round(((correctCount + fixedCount) / itemCount) * 10000) / 100;
    document.getElementById("acuracy").textContent = accuracy + "% acuracy";
    actualAccuracy = Math.round((correctCount / itemCount) * 10000) / 100;
    document.getElementById("actualAcuracy").textContent =
        actualAccuracy + "% actual accuracy";
    lessonNumber = document.getElementById("lessonNumber").value;
    addResult(lessonNumber, wordsPerMinute, accuracy, actualAccuracy);
    document.getElementById("goBack").style.visibility = "visible";
    document.getElementById("spacebar").style.opacity = "1";
};

const startWordCounter = () => {
    var start = Date.now();
    timerInterval = setInterval(function() {
        var seconds = Math.floor((Date.now() - start) / 1000);
        var wordNumber = document
            .getElementsByClassName("active")[0]
            .parentElement.getAttribute("wordNumber");
        wordsPerSecond = Math.round((wordNumber / seconds) * 100) / 100;
        console.log(wordsPerSecond);
        if (wordsPerSecond == Infinity) {
            wordsPerSecond = 0;
        }
        document.getElementById("wordsPerMinute").textContent =
            Math.round(wordsPerSecond * 60 * 100) / 100 + " w/min";
    }, 100);
};

const hasAndRemoveClass = (element, className, removeClass = true) => {
    for (const item of element.className.split(" ")) {
        if (item === className) {
            if (removeClass) {
                element.className = element.className.replace(className, "");
            }
            return true;
        }
    }
    return false;
};

const getActiveCharID = (list) => {
    for (let index = 0; index < list.length; index++) {
        for (const item of list[index].className.split(" ")) {
            if (item === "active") {
                return index;
            }
        }
    }
};

const getPreviousChar = (charList, activeID) => {
    var previousChar = undefined;
    if (activeID - 1 >= 0) {
        previousChar = charList[activeID - 1];
    }
    return previousChar;
};
const getNextChar = (charList, activeID) => {
    var nextChar = undefined;
    if (activeID + 1 < charList.length) {
        nextChar = charList[activeID + 1];
    }
    return nextChar;
};

const addResult = (lessonNumber, wordPerMinute, accuracy, actualAccuracy) => {
    jQuery.ajax({
        type: "POST",
        url: "addStats.php",
        data: "lessonNumber=" +
            lessonNumber +
            "&wordPerMinute=" +
            wordPerMinute +
            "&accuracy=" +
            accuracy +
            "&actualAccuracy=" +
            actualAccuracy,
        success: function(obj, textstatus) {
            if (!("error" in obj)) {
                yourVariable = obj.result;
            } else {
                console.log(obj.error);
            }
        },
    });
};