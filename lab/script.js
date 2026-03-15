let a = 5;
let b = 10;
[a, b] = [b, a];
console.log(a, b);


function square(n) {
    return n * n;
}

for (let i = 1; i <= 10; i++) {
    console.log(square(i));
}


let numbers = [10, 25, 5, 40, 15];

let largest = numbers[0];

for (let i = 1; i < numbers.length; i++) {
    if (numbers[i] > largest) {
        largest = numbers[i];
    }
}

console.log(largest);