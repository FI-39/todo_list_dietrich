const container = document.getElementById('container');
const p = document.createElement('p');
p.textContent = 'Hallo Welt!';
container.appendChild(p);

const headline = document.createElement('h2');
headline.textContent = 'Neue Überschrift';
container.appendChild(headline);
 
console.log(p);

const div = document.createElement('div');

const h2 = document.createElement('h2');
h2.textContent = 'Meine Überschrift';

const p1 = document.createElement('p');
p1.textContent = 'Mein Text';

div.appendChild(h2);
div.appendChild(p1);

console.log(div);
document.getElementById('container').appendChild(div);

function clickHandler() {
console.log(h2);
h2.textContent += ' klick';

const list = document.getElementById('list');
list.innerHTML = '';
 
const fruits = ['Apfel', 'Banane', 'Kirsche'];
fruits.forEach(fruit => {
    const li = document.createElement('li');
    li.textContent = fruit;
    list.appendChild(li);
});
}