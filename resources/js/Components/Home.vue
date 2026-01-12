<template>
    <div class="calendar">
        <div class="header">
            <button @click="prevMonth">‹</button>
            <h2>{{ monthNames[currentMonth] }} {{ currentYear }}</h2>
            <button @click="nextMonth">›</button>
        </div>

        <div class="weekdays">
            <div v-for="day in weekDays" :key="day">{{ day }}</div>
        </div>

        <div class="days">
            <div
                v-for="(day, index) in calendarDays"
                :key="index"
                :class="['day', { 'empty': day === null, 'today': isToday(day) }]"
            >
                {{ day }}
            </div>
        </div>
    </div><br>

    <div>
        <form @submit.prevent="send" >
            <label for="from">From</label><br>
            <input  id="from"  type="datetime-local" @change = "onSubmit" v-model="from"><br>
            <label for="to">To</label><br>
            <input  id="to"  type="datetime-local" @change = "onSubmit" v-model="to">
            <div >For {{time}} hour {{price}}$</div>
            <button >submit</button>
        </form>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import axios from "axios";


const weekDays = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());
const from = ref('');
const to = ref('');
const price = ref(0);
const diffMinutes = ref(null);
const time = ref(null);
function calculate(fromVal, toVal) {
    const d1 = new Date(fromVal);
    const d2 = new Date(toVal);
    let diff = d2 - d1;
    diff = diff / 1000 / 60;
    return  Math.abs(diff);
}

function onSubmit() {
    if (!from.value) {
        alert("Select the start date");
        return;
    }

    if (to.value) {
        diffMinutes.value = calculate(from.value, to.value);
        time.value = (diffMinutes.value / 60);
        price.value = (diffMinutes.value / 60 * 12);

    } else {
        time.value = '1';
        price.value = 12;
    }
}

function send(price, from, to){

}
function prevMonth() {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value -= 1;
    } else {
        currentMonth.value -= 1;
    }
}

function nextMonth() {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value += 1;
    } else {
        currentMonth.value += 1;
    }
}

const calendarDays = computed(() => {
    const firstDay = new Date(currentYear.value, currentMonth.value, 1).getDay();
    const lastDate = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
    const daysArray = [];

    const emptyDays = (firstDay + 6) % 7;
    for (let i = 0; i < emptyDays; i++) {
        daysArray.push(null);
    }

    for (let day = 1; day <= lastDate; day++) {
        daysArray.push(day);
    }

    return daysArray;
});

function isToday(day) {
    return (
        day === today.getDate() &&
        currentMonth.value === today.getMonth() &&
        currentYear.value === today.getFullYear()
    );
}
</script>

<style scoped>
.calendar {
    width: 320px;
    border: 1px solid #ccc;
    padding: 10px;
    font-family: sans-serif;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.weekdays, .days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    margin-top: 10px;
}

.day {
    padding: 8px;
    margin: 2px;
    border-radius: 4px;
}

.empty {
    background: #f0f0f0;
}

.today {
    border: 1px solid #4caf50;
}
</style>

<style scoped>
.calendar {
    width: 300px;
    border: 1px solid #ccc;
    padding: 10px;
    font-family: sans-serif;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.weekdays, .days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    margin-top: 10px;
}

.day {
    padding: 8px;
    margin: 2px;
    border-radius: 4px;
}

.empty {
    background: #f0f0f0;
}

.today {
    background: #4caf50;
    color: white;
}
</style>
