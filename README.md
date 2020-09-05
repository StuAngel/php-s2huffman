# php-s2huffman
web / url safe compression

web / url safe Huffman Greedy Algo-3

I was sick to death of using Base64 encoding as it can potentially make a string up to 3 times its original size so I have implemented the Huffman Greedy Algo-3, now I can actually send data between client and server with smaller that original rather than larger packets.

$huffman = new s2huffman();

$c = $huffman->compress("aaaaabbbbbbbbbccccccccccccdddddddddddddeeeeeeeeeeeeeeeefffffffffffffffffffffffffffffffffffffffffffff");

/* $c equals s2[66ML63M64]ML61M62]M65K].00e0CCCCCDDDDDDDDD924924924B6DB6DB6DBFFFFFFFFFFFE00000000000 */

$result = $huffman->decompress(c);

/* $result equals aaaaabbbbbbbbbccccccccccccdddddddddddddeeeeeeeeeeeeeeeefffffffffffffffffffffffffffffffffffffffffffff */
