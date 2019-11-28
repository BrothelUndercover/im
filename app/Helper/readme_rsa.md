$rsa = new Rsa();

echo "openssl_private_encrypt,openssl_public_decrypt","<br />";
//私钥加密，公钥解密
 echo "私钥加密,公钥验签","<br />";
 echo "待加密数据：testInfo","<br />";
 $pre = $rsa->privEncrypt("testInfo");
 echo "加密后的密文:<br />" . $pre . "<br />";
 $pud = $rsa->pubDecrypt($pre);
 echo "解密后数据:" . $pud . "<br />";
 echo "<hr>";


 //公钥加密，私钥解密
 echo "openssl_public_encrypt,openssl_private_decrypt","<br />";
 echo "公钥加密，私钥验签","<br />";
 echo "待加密数据：ssh-test","<br />";
 $pue = $rsa->pubEncrypt("ssh-test");
 echo "加密后的密文:","<br />" . $pue . "<br />";
 $prd = $rsa->privDecrypt($pue);
 echo "解密后数据:" . $prd;

 echo "<hr>";echo "<hr>";

 echo "openssl_sign,openssl_verify","<br />";
 echo "私钥签名,公钥验签","<br />";
 echo "待加密数据：test=32","<br />";
 $pre = $rsa->priKeySign('test=32');
 echo "加密后的密文:","<br />" . $pre . "<br />";
 $pud = $rsa->pubKeyCheck($pre,'test=32');
 echo "是否解密成功:" . $pud . "<br />";
 echo "<hr>";
