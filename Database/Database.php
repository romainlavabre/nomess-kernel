<?php

namespace NoMess\Database;

use NoMess\DataManager\DataManager;


/**
 * ### Database interagit avec le Géstionnaire de donnée afin de créer une couche d'abstraction entre le développeur et cette tache.
 * 
 * Il facilite ainsi le développement et les testes unitaires.
 * DataManager interagit directement avc vos class de persistances, il est développé 
 * dans l'objectif de gérer les dépendance de vos objets, ses encapsulations et garantir la cohérence de vos données, tant au niveau de la base de donnée 
 * que de la session, tout en conservant des performances optimales.
 * Le DataManager n'est pas compatible avec l'utilisation d'un ORM.
 * 
 * Pour utiliser le géstionnaire de données, les configurations se situe au niveau des objets via des annotations, elle consiste éssentiellement à mapper 
 * la class de persistance associé, ses dépendances, sa localisation en session et quelques autre options.
 * Pour une configuration pendant l'éxecution, une documentation se trouve directement dans la class Database
 */
class Database
{

    private const KEY           = 'nomess_db';
    private const CREATE        = 'create:';
    private const UPDATE        = 'update:';
    private const DELETE        = 'delete:';


    /**
     *
     * @var DataManager
     */
    private $datamanager;


    /**
     * @Inject
     *
     * @param DataManager $dataManager
     */
    public function __construct(DataManager $dataManager)
    {   
        $this->datamanager = $dataManager;
    }


    /**
     * ### Mets la ***création*** de l'objet cible dans la file d'attente
     * 
     *
     * @param array $param Tableau d'objet, par défaut, la cible est le première objet mit en paramètre, 
     * si pour une raison quelconque vous ne pouvez le mettre en premère position, précisez le type ***(Voir $type)***
     * 
     * @param array|null $dependancy Par defaut, les dépendances de la cible seront recherché dans les objets traité de 
     * la même requête, si plusieurs objets du même type sont présent, le premier sera retenu. **Pour modifier ce comportement**, 
     * passez par référance les dépendances de l'objet cible, vous n'êtes pas obligé de passer **TOUTE** ses dépendances, 
     * ***seulement celle qui ne seront par séléctionnez par defaut*** par le géstionnaire 
     * 
     * @param array|null $runtimeConfig Configuaration à l'éxecution, permet de désactiver temporairement une configuration ou d'en ajouter une
     * #### Format
     * `['depend' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/string('Full\Quanlified\class::methodName')/mixed value,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/string('Full\Quanlified\class::methodName')/mixed value`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`],`
     * 
     * `'transation' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => true/false,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `...`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *      `],`
     * 
     * `'insert' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/'nomess_backTransaction'/mixed value,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `...`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *      `]`
     * 
     * `]`
     * 
     * Annulez, ajouter ou injecter manuellement une dépendance
     * Annulez ou ajouter une transaction (dans les cas d'encapsulation),
     * Annulez, créer ou modifier une insertion
     * 
     * 
     * @param string $type Si vous ne pouvez remplir les conditions de **$param** ***(Voir $param)***, précisez le type 
     * de l'objet cible (comprenant sont namespace)
     *
     * @return void
     */
    public function create(array $param, ?array $dependancy = null, ?array $runtimeConfig = null, string $type = null) : void
	{
		$_SESSION[self::KEY][] =[
            'request' => [self::CREATE . $type => $param],
            'depend' => $dependancy,
            'runtimeConfig' => $runtimeConfig
            ];
	}




    /**
     * ### Mets la ***mise à jour*** de l'objet cible dans la file d'attente
     *
     * Pour **assurer la cohérance des données** si vous travaillez avec les sessions, passez toujours **les objets par valeurs et non par référance**, 
     * les objets orginaux se trouvant en session seront par la suite remplacé par les objets passé en paramètre ***(à condition qu'un clé "keyArray" soit défini)***
     *
     * @param array $param Tableau d'objet, par défaut, la cible est le première objet mit en paramètre, 
     * si pour une raison quelconque vous ne pouvez le mettre en premère position, précisez le type ***(Voir $type)***
     * 
     * @param array|null $dependancy Par defaut, les dépendances de la cible seront recherché dans les objets traité de 
     * la même requête, si plusieurs objets du même type sont présent, le premier sera retenu. **Pour modifier ce comportement**, 
     * passez par référance les dépendances de l'objet cible, vous n'êtes pas obligé de passer **TOUTE** ses dépendances, 
     * ***seulement celle qui ne seront par séléctionnez par defaut*** par le géstionnaire 
     * 
     * 
     * @param array|null $runtimeConfig Configuaration à l'éxecution, permet de désactiver temporairement une configuration ou d'en ajouter une
     * #### Format
     * `['depend' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/string('Full\Quanlified\class::methodName')/mixed value,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/string('Full\Quanlified\class::methodName')/mixed value`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`],`
     * 
     * `'transation' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => true/false,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `...`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *      `],`
     * 
     * `'insert' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/'nomess_backTransaction'/mixed value,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `...`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *      `]`
     * 
     * `]`
     * 
     * Annulez, ajouter ou injecter manuellement une dépendance
     * Annulez ou ajouter une transaction (dans les cas d'encapsulation),
     * Annulez, créer ou modifier une insertion
     * 
     * @param string $type Si vous ne pouvez remplir les conditions de **$param** ***(Voir $param)***, précisez le type 
     * de l'objet cible (comprenant sont namespace)
     *
     * @return void
     */
	public function update(array $param, ?array $dependancy = null, ?array $runtimeConfig = null, string $type = null) : void
	{
		$_SESSION[self::KEY][] = [
            'request' => [self::UPDATE . $type => $param],
            'depend' => $dependancy, 
            'runtimeConfig' => $runtimeConfig
        ];
	}


    /**
     * ### Mets la ***suppréssion*** de l'objet cible dans la file d'attente
     *
     * Pour **assurer la cohérance des données** si vous travaillez avec les sessions, passez toujours **les objets par valeurs et non par référance**, 
     * les objets orginaux se trouvant en session seront par la suite remplacé par les objets passé en paramètre 
     * ***à condition qu'un clé "keyArray" soit définie et que les transaction se soit déroulé correctement***
     *
     * @param array $param Tableau d'objet, par défaut, la cible est le première objet mit en paramètre, 
     * si pour une raison quelconque vous ne pouvez le mettre en premère position, précisez le type ***(Voir $type)***
     * 
     * @param array|null $dependancy Par defaut, les dépendances de la cible seront recherché dans les objets traité de 
     * la même requête, si plusieurs objets du même type sont présent, le premier sera retenu. **Pour modifier ce comportement**, 
     * passez par référance les dépendances de l'objet cible, vous n'êtes pas obligé de passer **TOUTE** ses dépendances, 
     * ***seulement celle qui ne seront par séléctionnez par defaut*** par le géstionnaire 
     * 
     * @param array|null $runtimeConfig Configuaration à l'éxecution, permet de désactiver temporairement une configuration ou d'en ajouter une
     * #### Format
     * `['depend' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/string('Full\Quanlified\class::methodName')/mixed value,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/string('Full\Quanlified\class::methodName')/mixed value`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`],`
     * 
     * `'transation' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => true/false,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `...`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *      `],`
     * 
     * `'insert' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/'nomess_backTransaction'/mixed value,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `...`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *      `]`
     * 
     * `]`
     * 
     * Annulez, ajouter ou injecter manuellement une dépendance
     * Annulez ou ajouter une transaction (dans les cas d'encapsulation),
     * Annulez, créer ou modifier une insertion
     * 
     * @param string $type Si vous ne pouvez remplir les conditions de **$param** ***(Voir $param)***, précisez le type 
     * de l'objet cible (comprenant sont namespace)
     *
     * @return void
     */
	public function delete(array $param, ?array $dependancy = null, ?array $runtimeConfig = null, string $type = null) : void
	{
		$_SESSION[self::KEY][] = [
            'request' => [self::DELETE . $type => $param],
            'depend' => $dependancy, 
            'runtimeConfig' => $runtimeConfig
        ];
	}


    /**
     * ### Mets une requête défini par vos soins de l'objet cible dans la file d'attente
     *
     * Vous permet d'éffactuer une transaction pour une method ayant une appelation différente du classique "create, read, update, delete".
     * 
     * #### ATTENTION si vous utilisez update ou delete:
     * Pour **assurer la cohérance des données** si vous travaillez avec les sessions, passez toujours **les objets par valeurs et non par référance**, 
     * les objets orginaux se trouvant en session seront par la suite remplacé par les objets passé en paramètre 
     * ***à condition qu'un clé "keyArray" soit définie et que les transaction se soit déroulé correctement***
     *
     * 
     * @param string $method Contient le nom de la method à appeler dans la class de persistance (pensez a créér un alias au sein de l'objet)
     * 
     * @param array $param Tableau d'objet, par défaut, la cible est le première objet mit en paramètre, 
     * si pour une raison quelconque vous ne pouvez le mettre en premère position, précisez le type ***(Voir $type)***
     * 
     * @param array|null $dependancy Par defaut, les dépendances de la cible seront recherché dans les objets traité de 
     * la même requête, si plusieurs objets du même type sont présent, le premier sera retenu. **Pour modifier ce comportement**, 
     * passez par référance les dépendances de l'objet cible, vous n'êtes pas obligé de passer **TOUTE** ses dépendances, 
     * ***seulement celle qui ne seront par séléctionnez par defaut*** par le géstionnaire 
     * 
     * @param array|null $runtimeConfig Configuaration à l'éxecution, permet de désactiver temporairement une configuration ou d'en ajouter une
     * #### Format
     * `['depend' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/string('Full\Quanlified\class::methodName')/mixed value,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/string('Full\Quanlified\class::methodName')/mixed value`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`],`
     * 
     * `'transation' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => true/false,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `...`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *      `],`
     * 
     * `'insert' => [`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `'setterMethod' => false/'nomess_backTransaction'/mixed value,`
     * 
     *          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *          `...`
     * 
     *      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     *      `]`
     * 
     * `]`
     * 
     * Annulez, ajouter ou injecter manuellement une dépendance
     * Annulez ou ajouter une transaction (dans les cas d'encapsulation),
     * Annulez, créer ou modifier une insertion
     * 
     * @param string $type Si vous ne pouvez remplir les conditions de **$param** ***(Voir $param)***, précisez le type 
     * de l'objet cible (comprenant sont namespace)
     *
     * @return void
     */
	public function database(string $method, array $param, ?array $dependancy = null, ?array $runtimeConfig = null, string $type = null) : void
	{
		$_SESSION[self::KEY][] = [
            'request' => [$method . ':' . $type => $param],
            'depend' => $dependancy, 
            'runtimeConfig' => $runtimeConfig
        ];
    }
    

    /**
     * ### Lance la procedure de transaction en executant toute les opérations en attente, si une erreur survient, false sera retourné, true sinon
     *
     * @return bool
     */
    public function manage() : bool
    {
        return $this->datamanager->database();
    }
}