const express = require('express');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.urlencoded({ extended: false }));

app.post('/', (req, res) => {
  const { sessionId, serviceCode, phoneNumber, text } = req.body;

  let response = '';

  if (text === '') {
    response = `CON Hitamo Uburyo bw'urugendo:
1. Imodoka
2. moto
3. Igare
4. Busi`;
  } else if (text === '1') {
    response = 'END Wahisemo Imodoka. Murakoze!';
  } else if (text === '2') {
    response = 'END Wahisemo moto. Murakoze!';
  } else if (text === '3') {
    response = 'END Wahisemo Igare. Murakoze!';
  } else if (text === '4') {
    response = 'END Wahisemo Busi. Murakoze!';
  } else {
    response = 'END Icyo wahisemo nticyumvikanye.';
  }

  res.set('Content-Type', 'text/plain');
  res.send(response);
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`USSD app running on port ${PORT}`);
});