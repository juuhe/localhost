<?php

class LUDecomposition
{
	/**
	 *	Decomposition storage
	 *	@var array
	 */
	private $LU = array();
	/**
	 *	Row dimension.
	 *	@var int
	 */
	private $m;
	/**
	 *	Column dimension.
	 *	@var int
	 */
	private $n;
	/**
	 *	Pivot sign.
	 *	@var int
	 */
	private $pivsign;
	/**
	 *	Internal storage of pivot vector.
	 *	@var array
	 */
	private $piv = array();

	public function __construct($A)
	{
		if ($A instanceof Matrix) {
			$this->LU = $A->getArrayCopy();
			$this->m = $A->getRowDimension();
			$this->n = $A->getColumnDimension();

			for ($i = 0; $i < $this->m; ++$i) {
				$this->piv[$i] = $i;
			}

			$this->pivsign = 1;
			$LUrowi = $LUcolj = array();

			for ($j = 0; $j < $this->n; ++$j) {
				for ($i = 0; $i < $this->m; ++$i) {
					$LUcolj[$i] = &$this->LU[$i][$j];
				}

				for ($i = 0; $i < $this->m; ++$i) {
					$LUrowi = $this->LU[$i];
					$kmax = min($i, $j);
					$s = 0;

					for ($k = 0; $k < $kmax; ++$k) {
						$s += $LUrowi[$k] * $LUcolj[$k];
					}

					$LUrowi[$j] = $LUcolj[$i] -= $s;
				}

				$p = $j;

				for ($i = $j + 1; $i < $this->m; ++$i) {
					if (abs($LUcolj[$p]) < abs($LUcolj[$i])) {
						$p = $i;
					}
				}

				if ($p != $j) {
					for ($k = 0; $k < $this->n; ++$k) {
						$t = $this->LU[$p][$k];
						$this->LU[$p][$k] = $this->LU[$j][$k];
						$this->LU[$j][$k] = $t;
					}

					$k = $this->piv[$p];
					$this->piv[$p] = $this->piv[$j];
					$this->piv[$j] = $k;
					$this->pivsign = $this->pivsign * -1;
				}

				if (($j < $this->m) && ($this->LU[$j][$j] != 0)) {
					for ($i = $j + 1; $i < $this->m; ++$i) {
						$this->LU[$i][$j] /= $this->LU[$j][$j];
					}
				}
			}
		}
		else {
			throw new Exception(JAMAError(ArgumentTypeException));
		}
	}

	public function getL()
	{
		for ($i = 0; $i < $this->m; ++$i) {
			for ($j = 0; $j < $this->n; ++$j) {
				if ($j < $i) {
					$L[$i][$j] = $this->LU[$i][$j];
				}
				else if ($i == $j) {
					$L[$i][$j] = 1;
				}
				else {
					$L[$i][$j] = 0;
				}
			}
		}

		return new Matrix($L);
	}

	public function getU()
	{
		for ($i = 0; $i < $this->n; ++$i) {
			for ($j = 0; $j < $this->n; ++$j) {
				if ($i <= $j) {
					$U[$i][$j] = $this->LU[$i][$j];
				}
				else {
					$U[$i][$j] = 0;
				}
			}
		}

		return new Matrix($U);
	}

	public function getPivot()
	{
		return $this->piv;
	}

	public function getDoublePivot()
	{
		return $this->getPivot();
	}

	public function isNonsingular()
	{
		for ($j = 0; $j < $this->n; ++$j) {
			if ($this->LU[$j][$j] == 0) {
				return false;
			}
		}

		return true;
	}

	public function det()
	{
		if ($this->m == $this->n) {
			$d = $this->pivsign;

			for ($j = 0; $j < $this->n; ++$j) {
				$d *= $this->LU[$j][$j];
			}

			return $d;
		}
		else {
			throw new Exception(JAMAError(MatrixDimensionException));
		}
	}

	public function solve($B)
	{
		if ($B->getRowDimension() == $this->m) {
			if ($this->isNonsingular()) {
				$nx = $B->getColumnDimension();
				$X = $B->getMatrix($this->piv, 0, $nx - 1);

				for ($k = 0; $k < $this->n; ++$k) {
					for ($i = $k + 1; $i < $this->n; ++$i) {
						for ($j = 0; $j < $nx; ++$j) {
							$X->A[$i][$j] -= $X->A[$k][$j] * $this->LU[$i][$k];
						}
					}
				}

				for ($k = $this->n - 1; 0 <= $k; --$k) {
					for ($j = 0; $j < $nx; ++$j) {
						$X->A[$k][$j] /= $this->LU[$k][$k];
					}

					for ($i = 0; $i < $k; ++$i) {
						for ($j = 0; $j < $nx; ++$j) {
							$X->A[$i][$j] -= $X->A[$k][$j] * $this->LU[$i][$k];
						}
					}
				}

				return $X;
			}
			else {
				throw new Exception(JAMAError(MatrixSingularException));
			}
		}
		else {
			throw new Exception(JAMAError(MatrixSquareException));
		}
	}
}


?>
