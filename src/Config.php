<?php
/**
 * Research/Config
 *
 * @author Joshua Copeland <JoshuaRCopeland@gmail.com>
 */

namespace Research;


class Config
{
    /**
     * @var string
     */
    private $iniFile;

    /**
     * Config constructor.
     * @param string $iniConfig
     */
    public function __construct(string $iniConfig = '/opt/research.ini')
    {
        $this->iniFile = $iniConfig;
    }

    /**
     * @return array|bool
     * @throws \Exception
     */
    public function getConfig()
    {
        // Parse without sections
        $ini_array = parse_ini_file($this->iniFile, true);
        if (empty($ini_array)) {
            throw new \Exception(sprintf('The ini file was missing: %s', $this->iniFile));
        }
        return $ini_array;
    }

    /**
     * @return mixed
     */
    public function getEmailConfigs()
    {
        $config = $this->getConfig();
        return $config['email'];
    }

    /**
     * @return array
     */
    public function getBizAdmins()
    {
        $config = $this->getConfig();
        $to = [];
        foreach ($config['biz_admins']['to'] as $email) {
            $explodedEmail = explode(',', $email);
            if (!empty($explodedEmail[1])) {
                $to[$explodedEmail[0]] = $explodedEmail[1];
            } elseif (!empty($explodedEmail[0])) {
                $to[$explodedEmail[0]] = $explodedEmail[0];
            }
        }
        return $to;
    }
}