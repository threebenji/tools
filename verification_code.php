<?php

class Code
{
    const secret_default = '&f9dfadfa21233242x4dax12333%4536';
    private $bit = 0;
    private $tolerateMin = 0;
    private $businessCode = "";

    /**
     * Code constructor.
     * @param int $bit 长度
     * @param int $tolerateMin 有效时长
     * @param string $businessCode 每个产品线做区分
     */
    function __construct(int $bit, int $tolerateMin, string $businessCode)
    {
        $this->bit = $bit;
        $this->tolerateMin = $tolerateMin;
        $this->businessCode = $businessCode;
    }

    /**
     * 生成Code
     * @param string $phone
     * @return string
     */
    public function NewCode(string $phone): string
    {
        $now = time();
        return $this->_generateCode($now, $this->businessCode, $phone, self::secret_default, $this->bit);
    }

    /**
     * 验证码验证
     * @param string $code
     * @param string $phone
     * @return bool
     */
    public function ValidateCode(string $code, string $phone): bool
    {
        $now = time();
        for ($i = 0; $i < $this->tolerateMin; $i++) {
            $c = $this->_generateCode($now - $i, $this->businessCode, $phone, self::secret_default, $this->bit);
            if ($c === $code) {
                return true;
            }
        }
        return false;
    }

    private function _generateCode(int $time, string $businessCode, string $phone, string $secret, int $bit): string
    {
        $rawString = sprintf("%d,%s,%s,%s", $time, $businessCode, $phone, $secret);
        $num = crc32($rawString);
        if (strlen($num) > $bit) {
            $ret = substr($num, strlen($num) - $bit);
        } else {
            $ret = sprintf("%0{$bit}d", $num);
        }
        return $ret;
    }

}

$code = new Code(6, 200, "用户登录");
$c = $code->NewCode("13800138000");
var_dump($code->ValidateCode("589693","13800138000"),$c);
