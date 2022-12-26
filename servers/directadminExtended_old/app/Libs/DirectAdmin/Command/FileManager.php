<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\Directory;

class FileManager extends AbstractCommand
{
    const FOLDER           = 'folder';
    const MULTIPLE         = 'multiple';

    const CMD_FILE_MANAGER              = 'CMD_API_FILE_MANAGER';
    const CMD_API_ADMIN_FILE_EDITOR     = 'CMD_API_ADMIN_FILE_EDITOR';
    const CMD_MANAGER                   = 'CMD_FILE_MANAGER';
    const CMD_PROTECTED_DIRECTORIES     = 'CMD_PROTECTED_DIRECTORIES';

    /**
     * list all files and their info for a given path
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function lists(Models\Command\FileManager $fileManager)
    {
        $response = $this->curl->request(self::CMD_FILE_MANAGER, [
            'path'      => $fileManager->getPath(),

        ], ['action' => 'json_all', 'json' => 'yes', 'page' => 1, 'ipp' => 500]);

        return $this->loadResponse(new Models\Command\FileManager(), $response);
    }

    /**
     * compress file
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function compress(Models\Command\FileManager $fileManager)
    {
        return $this->curl->request(self::CMD_FILE_MANAGER, [
            'action'    => __FUNCTION__,
            'path'      => $fileManager->getPath(),
            'name'      => $fileManager->getName()
        ]);
    }

    /**
     * extract file
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function extract(Models\Command\FileManager $fileManager)
    {
        return $this->curl->request(self::CMD_FILE_MANAGER, [
            'action'    => __FUNCTION__,
            'directory' => $fileManager->getDirectory()
        ]);
    }

    /**
     * rename file or directory
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function rename(Models\Command\FileManager $fileManager)
    {
        return $this->curl->request(self::CMD_FILE_MANAGER, [
            'action'    => __FUNCTION__,
            'path'      => $fileManager->getPath(),
            'old'       => $fileManager->getOld(),
            'filename'  => $fileManager->getName(),
            'overwrite' => $fileManager->getOverwrite()
        ]);
    }

    /**
     * copy file
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function copy(Models\Command\FileManager $fileManager)
    {
        return $this->curl->request(self::CMD_FILE_MANAGER, [
            'action'    => __FUNCTION__,
            'path'      => $fileManager->getPath(),
            'old'       => $fileManager->getOld(),
            'filename'  => $fileManager->getName(),
            'overwrite' => $fileManager->getOverwrite()
        ]);
    }

    /**
     * create directory
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function createDirectory(Models\Command\FileManager $fileManager)
    {
        return $this->curl->request(self::CMD_FILE_MANAGER, [
            'action'    => self::FOLDER,
            'path'      => $fileManager->getPath(),
            'name'      => $fileManager->getName()
        ]);
    }

    /**
     * set files permissions
     *
     * @param array
     * @return mixed
     */
    public function permission(array $data)
    {
        $params = [
            'action'    => self::MULTIPLE,
            'button'    => __FUNCTION__,
        ];
        foreach ($data as $key => $fileManager) {
            $params['chmod']       = (string) $fileManager->getChmod();
            $params['path']        = $fileManager->getPath();
            $params['select' . $key] = $fileManager->getTruepath();
        }
        return $this->curl->request(self::CMD_FILE_MANAGER, $params);
    }

    /**
     * delete file
     *
     * @param array
     * @return mixed
     */
    public function delete(array $data)
    {
        $params = [
            'action'    => self::MULTIPLE,
            'button'    => __FUNCTION__,
        ];
        foreach ($data as $key => $fileManager) {
            $params['path']        = $fileManager->getPath();
            $params['select' . $key] = $fileManager->getTruepath();
        }

        return $this->curl->request(self::CMD_FILE_MANAGER, $params);
    }

    /**
     * upload a file
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function upload(Models\Command\FileManager $fileManager)
    {
        return $this->curl->request(self::CMD_FILE_MANAGER, [
            'action'    => __FUNCTION__,
            'enctype'   => "multipart/form-data",
            'path'      => $fileManager->getPath(),
            'name'      => $fileManager->getName(),
            'file1'     => $fileManager->getFile()
        ]);
    }

    /**
     * view a file
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function view(Models\Command\FileManager $fileManager)
    {
        $this->curl->request(self::CMD_MANAGER, [], [
            'path'   => $fileManager->getPath()
        ]);

        return $this->loadResponse(new Models\Command\FileManager(), $this->curl->getRawResponse(), __FUNCTION__);
    }

    /**
     * edit a file
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function edit(Models\Command\FileManager $fileManager)
    {
        return $this->curl->request(self::CMD_FILE_MANAGER, [
            'action'    => __FUNCTION__,
            'filename'  => $fileManager->getName(),
            'path'      => $fileManager->getPath(),
            'text'      => $fileManager->getText()
        ], [], true);
    }

    public function downloadFile(Models\Command\FileManager $fileManager)
    {
        return $this->curl->downloadRequest(self::CMD_MANAGER, [
            'path'      => $fileManager->getPath(),
        ], [], true);
    }

    /**
     * set password to directory
     *
     * @param Models\Command\FileManager $fileManager
     * @return mixed
     */
    public function protect(Models\Command\FileManager $fileManager)
    {   
        return $this->curl->request(self::CMD_MANAGER, [
            'action'    => __FUNCTION__,
            'enabled'   => 'yes',
            'path'      => $fileManager->getPath(),
            'name'      => $fileManager->getName(),
            'user'      => $fileManager->getUser(),
            'passwd'    => $fileManager->getPassword(),
            'passwd2'   => $fileManager->getPassword(),
            'json'   => 'yes',
        ], [], true);
    }

    public function unprotect($fileManager)
    {
        return $this->curl->request(self::CMD_MANAGER, [
            'action'    => 'protect',
            'json'   => 'yes',
            'path'      => $fileManager->getPath(),
            'name'      => $fileManager->getName(),
        ], [], true);
    }

    public function protectedDirs($domain)
    {
        return $this->curl->request(self::CMD_PROTECTED_DIRECTORIES, [], [
            'json'   => 'yes',
            'ipp'    => 50,
            'domain' => $domain,
            'redirect'  => 'yes',
        ]);
    }

    public function getName($fileManager)
    {
        return $this->curl->request(self::CMD_MANAGER, [], [
            'action'    => 'protect',
            'json'   => 'yes',
            'path'      => $fileManager->getPath(),
            'ipp'   => 50,
        ], true);
    }
}
