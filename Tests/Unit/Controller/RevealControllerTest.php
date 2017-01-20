<?php
namespace TgM\TgmReveal\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 EG <eg@teamgeist-medien.de>, Teamgeist Medien
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class TgM\TgmReveal\Controller\RevealController.
 *
 * @author EG <eg@teamgeist-medien.de>
 */
class RevealControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \TgM\TgmReveal\Controller\RevealController
	 */
	protected $subject = NULL;

	public function setUp() {
		$this->subject = $this->getMock('TgM\\TgmReveal\\Controller\\RevealController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllRevealsFromRepositoryAndAssignsThemToView() {

		$allReveals = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$revealRepository = $this->getMock('TgM\\TgmReveal\\Domain\\Repository\\RevealRepository', array('findAll'), array(), '', FALSE);
		$revealRepository->expects($this->once())->method('findAll')->will($this->returnValue($allReveals));
		$this->inject($this->subject, 'revealRepository', $revealRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('reveals', $allReveals);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenRevealToView() {
		$reveal = new \TgM\TgmReveal\Domain\Model\Reveal();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('reveal', $reveal);

		$this->subject->showAction($reveal);
	}
}