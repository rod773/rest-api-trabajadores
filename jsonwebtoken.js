unsignedToken = base64url(header) + "." + base64url(data);
JWT = unsignedToken + "." + base64url(HMAC256(unsignedToken, secret));

function base64url(source) {
  // Encode in classical base64
  encodedSource = CryptoJS.enc.Base64.stringify(source);

  // Remove padding equal characters
  encodedSource = encodedSource.replace(/=+$/, "");

  // Replace characters according to base64url specifications
  encodedSource = encodedSource.replace(/\+/g, "-");
  encodedSource = encodedSource.replace(/\//g, "_");

  return encodedSource;
}

var source = "Hello!";

// 48 65 6c 6c 6f 21
console.log(CryptoJS.enc.Utf8.parse(source).toString());

var header = {
  alg: "HS256",
  typ: "JWT",
};

var stringifiedHeader = CryptoJS.enc.Utf8.parse(JSON.stringify(header));
var encodedHeader = base64url(stringifiedHeader);

var data = {
  id: 1337,
  username: "john.doe",
};

var stringifiedData = CryptoJS.enc.Utf8.parse(JSON.stringify(data));
var encodedData = base64url(stringifiedData);

var token = encodedHeader + "." + encodedData;

var secret = "My very confidential secret!";

var signature = CryptoJS.HmacSHA256(token, secret);
signature = base64url(signature);

var signedToken = token + "." + signature;
