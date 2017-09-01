<?php

class Matrix
{
	/**
	 *	Matrix storage
	 *
	 *	@var array
	 *	@access public
	 */
	public $A = array();
	/**
	 *	Matrix row dimension
	 *
	 *	@var int
	 *	@access private
	 */
	private $m;
	/**
	 *	Matrix column dimension
	 *
	 *	@var int
	 *	@access private
	 */
	private $n;

	public function __construct()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'integer':
				$this->m = $args[0];
				$this->n = $args[0];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, 0));
				break;

			case 'integer,integer':
				$this->m = $args[0];
				$this->n = $args[1];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, 0));
				break;

			case 'integer,integer,integer':
				$this->m = $args[0];
				$this->n = $args[1];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, $args[2]));
				break;

			case 'integer,integer,double':
				$this->m = $args[0];
				$this->n = $args[1];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, $args[2]));
				break;

			case 'array':
				$this->m = count($args[0]);
				$this->n = count($args[0][0]);
				$this->A = $args[0];
				break;

			case 'array,integer,integer':
				$this->m = $args[1];
				$this->n = $args[2];
				$this->A = $args[0];
				break;

			case 'array,integer':
				$this->m = $args[1];

				if ($this->m != 0) {
					$this->n = count($args[0]) / $this->m;
				}
				else {
					$this->n = 0;
				}

				if (($this->m * $this->n) == count($args[0])) {
					for ($i = 0; $i < $this->m; ++$i) {
						for ($j = 0; $j < $this->n; ++$j) {
							$this->A[$i][$j] = $args[0][$i + ($j * $this->m)];
						}
					}
				}
				else {
					throw new Exception(JAMAError(ArrayLengthException));
				}

				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function getArray()
	{
		return $this->A;
	}

	public function getArrayCopy()
	{
		return $this->A;
	}

	public function constructWithCopy($A)
	{
		$this->m = count($A);
		$this->n = count($A[0]);
		$newCopyMatrix = new Matrix($this->m, $this->n);

		for ($i = 0; $i < $this->m; ++$i) {
			if (count($A[$i]) != $this->n) {
				throw new Exception(JAMAError(RowLengthException));
			}

			for ($j = 0; $j < $this->n; ++$j) {
				$newCopyMatrix->A[$i][$j] = $A[$i][$j];
			}
		}

		return $newCopyMatrix;
	}

	public function getColumnPackedCopy()
	{
		$P = array();

		for ($i = 0; $i < $this->m; ++$i) {
			for ($j = 0; $j < $this->n; ++$j) {
				array_push($P, $this->A[$j][$i]);
			}
		}

		return $P;
	}

	public function getRowPackedCopy()
	{
		$P = array();

		for ($i = 0; $i < $this->m; ++$i) {
			for ($j = 0; $j < $this->n; ++$j) {
				array_push($P, $this->A[$i][$j]);
			}
		}

		return $P;
	}

	public function getRowDimension()
	{
		return $this->m;
	}

	public function getColumnDimension()
	{
		return $this->n;
	}

	public function get($i = NULL, $j = NULL)
	{
		return $this->A[$i][$j];
	}

	public function getMatrix()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'integer,integer':
				list($i0, $j0) = $args;

				if (0 <= $i0) {
					$m = $this->m - $i0;
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				if (0 <= $j0) {
					$n = $this->n - $j0;
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				$R = new Matrix($m, $n);

				for ($i = $i0; $i < $this->m; ++$i) {
					for ($j = $j0; $j < $this->n; ++$j) {
						$R->set($i, $j, $this->A[$i][$j]);
					}
				}

				return $R;
				break;

			case 'integer,integer,integer,integer':
				list($i0, $iF, $j0, $jF) = $args;
				if (($i0 < $iF) && ($iF <= $this->m) && (0 <= $i0)) {
					$m = $iF - $i0;
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				if (($j0 < $jF) && ($jF <= $this->n) && (0 <= $j0)) {
					$n = $jF - $j0;
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				$R = new Matrix($m + 1, $n + 1);

				for ($i = $i0; $i <= $iF; ++$i) {
					for ($j = $j0; $j <= $jF; ++$j) {
						$R->set($i - $i0, $j - $j0, $this->A[$i][$j]);
					}
				}

				return $R;
				break;

			case 'array,array':
				list($RL, $CL) = $args;

				if (0 < count($RL)) {
					$m = count($RL);
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				if (0 < count($CL)) {
					$n = count($CL);
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				$R = new Matrix($m, $n);

				for ($i = 0; $i < $m; ++$i) {
					for ($j = 0; $j < $n; ++$j) {
						$R->set($i - $i0, $j - $j0, $this->A[$RL[$i]][$CL[$j]]);
					}
				}

				return $R;
				break;

			case 'array,array':
				list($RL, $CL) = $args;

				if (0 < count($RL)) {
					$m = count($RL);
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				if (0 < count($CL)) {
					$n = count($CL);
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				$R = new Matrix($m, $n);

				for ($i = 0; $i < $m; ++$i) {
					for ($j = 0; $j < $n; ++$j) {
						$R->set($i, $j, $this->A[$RL[$i]][$CL[$j]]);
					}
				}

				return $R;
				break;

			case 'integer,integer,array':
				list($i0, $iF, $CL) = $args;
				if (($i0 < $iF) && ($iF <= $this->m) && (0 <= $i0)) {
					$m = $iF - $i0;
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				if (0 < count($CL)) {
					$n = count($CL);
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				$R = new Matrix($m, $n);

				for ($i = $i0; $i < $iF; ++$i) {
					for ($j = 0; $j < $n; ++$j) {
						$R->set($i - $i0, $j, $this->A[$RL[$i]][$j]);
					}
				}

				return $R;
				break;

			case 'array,integer,integer':
				list($RL, $j0, $jF) = $args;

				if (0 < count($RL)) {
					$m = count($RL);
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				if (($j0 <= $jF) && ($jF <= $this->n) && (0 <= $j0)) {
					$n = $jF - $j0;
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				$R = new Matrix($m, $n + 1);

				for ($i = 0; $i < $m; ++$i) {
					for ($j = $j0; $j <= $jF; ++$j) {
						$R->set($i, $j - $j0, $this->A[$RL[$i]][$j]);
					}
				}

				return $R;
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function setMatrix()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'integer,integer,object':
				if ($args[2] instanceof Matrix) {
					$M = $args[2];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				if (($args[0] + $M->m) <= $this->m) {
					$i0 = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				if (($args[1] + $M->n) <= $this->n) {
					$j0 = $args[1];
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				for ($i = $i0; $i < ($i0 + $M->m); ++$i) {
					for ($j = $j0; $j < ($j0 + $M->n); ++$j) {
						$this->A[$i][$j] = $M->get($i - $i0, $j - $j0);
					}
				}

				break;

			case 'integer,integer,array':
				$M = new Matrix($args[2]);

				if (($args[0] + $M->m) <= $this->m) {
					$i0 = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				if (($args[1] + $M->n) <= $this->n) {
					$j0 = $args[1];
				}
				else {
					throw new Exception(JAMAError(ArgumentBoundsException));
				}

				for ($i = $i0; $i < ($i0 + $M->m); ++$i) {
					for ($j = $j0; $j < ($j0 + $M->n); ++$j) {
						$this->A[$i][$j] = $M->get($i - $i0, $j - $j0);
					}
				}

				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function checkMatrixDimensions($B = NULL)
	{
		if ($B instanceof Matrix) {
			if (($this->m == $B->getRowDimension()) && ($this->n == $B->getColumnDimension())) {
				return true;
			}
			else {
				throw new Exception(JAMAError(MatrixDimensionException));
			}
		}
		else {
			throw new Exception(JAMAError(ArgumentTypeException));
		}
	}

	public function set($i = NULL, $j = NULL, $c = NULL)
	{
		$this->A[$i][$j] = $c;
	}

	public function identity($m = NULL, $n = NULL)
	{
		return $this->diagonal($m, $n, 1);
	}

	public function diagonal($m = NULL, $n = NULL, $c = 1)
	{
		$R = new Matrix($m, $n);

		for ($i = 0; $i < $m; ++$i) {
			$R->set($i, $i, $c);
		}

		return $R;
	}

	public function filled($m = NULL, $n = NULL, $c = 0)
	{
		if (is_int($m) && is_int($n) && is_numeric($c)) {
			$R = new Matrix($m, $n, $c);
			return $R;
		}
		else {
			throw new Exception(JAMAError(ArgumentTypeException));
		}
	}

	public function random($m = NULL, $n = NULL, $a = RAND_MIN, $b = RAND_MAX)
	{
		if (is_int($m) && is_int($n) && is_numeric($a) && is_numeric($b)) {
			$R = new Matrix($m, $n);

			for ($i = 0; $i < $m; ++$i) {
				for ($j = 0; $j < $n; ++$j) {
					$R->set($i, $j, mt_rand($a, $b));
				}
			}

			return $R;
		}
		else {
			throw new Exception(JAMAError(ArgumentTypeException));
		}
	}

	public function packed()
	{
		return $this->getRowPacked();
	}

	public function getMatrixByRow($i0 = NULL, $iF = NULL)
	{
		if (is_int($i0)) {
			if (is_int($iF)) {
				return $this->getMatrix($i0, 0, $iF + 1, $this->n);
			}
			else {
				return $this->getMatrix($i0, 0, $i0 + 1, $this->n);
			}
		}
		else {
			throw new Exception(JAMAError(ArgumentTypeException));
		}
	}

	public function getMatrixByCol($j0 = NULL, $jF = NULL)
	{
		if (is_int($j0)) {
			if (is_int($jF)) {
				return $this->getMatrix(0, $j0, $this->m, $jF + 1);
			}
			else {
				return $this->getMatrix(0, $j0, $this->m, $j0 + 1);
			}
		}
		else {
			throw new Exception(JAMAError(ArgumentTypeException));
		}
	}

	public function transpose()
	{
		$R = new Matrix($this->n, $this->m);

		for ($i = 0; $i < $this->m; ++$i) {
			for ($j = 0; $j < $this->n; ++$j) {
				$R->set($j, $i, $this->A[$i][$j]);
			}
		}

		return $R;
	}

	public function norm1()
	{
		$r = 0;

		for ($j = 0; $j < $this->n; ++$j) {
			$s = 0;

			for ($i = 0; $i < $this->m; ++$i) {
				$s += abs($this->A[$i][$j]);
			}

			$r = ($s < $r ? $r : $s);
		}

		return $r;
	}

	public function norm2()
	{
	}

	public function normInf()
	{
		$r = 0;

		for ($i = 0; $i < $this->m; ++$i) {
			$s = 0;

			for ($j = 0; $j < $this->n; ++$j) {
				$s += abs($this->A[$i][$j]);
			}

			$r = ($s < $r ? $r : $s);
		}

		return $r;
	}

	public function normF()
	{
		$f = 0;

		for ($i = 0; $i < $this->m; ++$i) {
			for ($j = 0; $j < $this->n; ++$j) {
				$f = hypo($f, $this->A[$i][$j]);
			}
		}

		return $f;
	}

	public function rank()
	{
		$svd = new SingularValueDecomposition($this);
		return $svd->rank();
	}

	public function cond()
	{
		$svd = new SingularValueDecomposition($this);
		return $svd->cond();
	}

	public function trace()
	{
		$s = 0;
		$n = min($this->m, $this->n);

		for ($i = 0; $i < $n; ++$i) {
			$s += $this->A[$i][$i];
		}

		return $s;
	}

	public function uminus()
	{
	}

	public function plus()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$M->set($i, $j, $M->get($i, $j) + $this->A[$i][$j]);
				}
			}

			return $M;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function plusEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						$this->A[$i][$j] += $value;
					}
					else {
						$this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
					}
				}
			}

			return $this;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function minus()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$M->set($i, $j, $M->get($i, $j) - $this->A[$i][$j]);
				}
			}

			return $M;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function minusEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						$this->A[$i][$j] -= $value;
					}
					else {
						$this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
					}
				}
			}

			return $this;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function arrayTimes()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$M->set($i, $j, $M->get($i, $j) * $this->A[$i][$j]);
				}
			}

			return $M;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function arrayTimesEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						$this->A[$i][$j] *= $value;
					}
					else {
						$this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
					}
				}
			}

			return $this;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function arrayRightDivide()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						if ($value == 0) {
							$M->set($i, $j, '#DIV/0!');
						}
						else {
							$M->set($i, $j, $this->A[$i][$j] / $value);
						}
					}
					else {
						$this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
					}
				}
			}

			return $M;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function arrayRightDivideEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$this->A[$i][$j] = $this->A[$i][$j] / $M->get($i, $j);
				}
			}

			return $M;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function arrayLeftDivide()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$M->set($i, $j, $M->get($i, $j) / $this->A[$i][$j]);
				}
			}

			return $M;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function arrayLeftDivideEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$this->A[$i][$j] = $M->get($i, $j) / $this->A[$i][$j];
				}
			}

			return $M;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function times()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$B = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				if ($this->n == $B->m) {
					$C = new Matrix($this->m, $B->n);

					for ($j = 0; $j < $B->n; ++$j) {
						for ($k = 0; $k < $this->n; ++$k) {
							$Bcolj[$k] = $B->A[$k][$j];
						}

						for ($i = 0; $i < $this->m; ++$i) {
							$Arowi = $this->A[$i];
							$s = 0;

							for ($k = 0; $k < $this->n; ++$k) {
								$s += $Arowi[$k] * $Bcolj[$k];
							}

							$C->A[$i][$j] = $s;
						}
					}

					return $C;
				}
				else {
					throw new Exception(JAMAError(MatrixDimensionMismatch));
				}

				break;

			case 'array':
				$B = new Matrix($args[0]);

				if ($this->n == $B->m) {
					$C = new Matrix($this->m, $B->n);

					for ($i = 0; $i < $C->m; ++$i) {
						for ($j = 0; $j < $C->n; ++$j) {
							$s = '0';

							for ($k = 0; $k < $C->n; ++$k) {
								$s += $this->A[$i][$k] * $B->A[$k][$j];
							}

							$C->A[$i][$j] = $s;
						}
					}

					return $C;
				}
				else {
					throw new Exception(JAMAError(MatrixDimensionMismatch));
				}

				return $M;
				break;

			case 'integer':
				$C = new Matrix($this->A);

				for ($i = 0; $i < $C->m; ++$i) {
					for ($j = 0; $j < $C->n; ++$j) {
						$C->A[$i][$j] *= $args[0];
					}
				}

				return $C;
				break;

			case 'double':
				$C = new Matrix($this->m, $this->n);

				for ($i = 0; $i < $C->m; ++$i) {
					for ($j = 0; $j < $C->n; ++$j) {
						$C->A[$i][$j] = $args[0] * $this->A[$i][$j];
					}
				}

				return $C;
				break;

			case 'float':
				$C = new Matrix($this->A);

				for ($i = 0; $i < $C->m; ++$i) {
					for ($j = 0; $j < $C->n; ++$j) {
						$C->A[$i][$j] *= $args[0];
					}
				}

				return $C;
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}
		}
		else {
			throw new Exception(PolymorphicArgumentException);
		}
	}

	public function power()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}

				break;

			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						$this->A[$i][$j] = pow($this->A[$i][$j], $value);
					}
					else {
						$this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
					}
				}
			}

			return $this;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function concat()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof Matrix) {
					$M = $args[0];
				}
				else {
					throw new Exception(JAMAError(ArgumentTypeException));
				}
			case 'array':
				$M = new Matrix($args[0]);
				break;

			default:
				throw new Exception(JAMAError(PolymorphicArgumentException));
				break;
			}

			$this->checkMatrixDimensions($M);

			for ($i = 0; $i < $this->m; ++$i) {
				for ($j = 0; $j < $this->n; ++$j) {
					$this->A[$i][$j] = trim($this->A[$i][$j], '"') . trim($M->get($i, $j), '"');
				}
			}

			return $this;
		}
		else {
			throw new Exception(JAMAError(PolymorphicArgumentException));
		}
	}

	public function chol()
	{
		return new CholeskyDecomposition($this);
	}

	public function lu()
	{
		return new LUDecomposition($this);
	}

	public function qr()
	{
		return new QRDecomposition($this);
	}

	public function eig()
	{
		return new EigenvalueDecomposition($this);
	}

	public function svd()
	{
		return new SingularValueDecomposition($this);
	}

	public function solve($B)
	{
		if ($this->m == $this->n) {
			$LU = new LUDecomposition($this);
			return $LU->solve($B);
		}
		else {
			$QR = new QRDecomposition($this);
			return $QR->solve($B);
		}
	}

	public function inverse()
	{
		return $this->solve($this->identity($this->m, $this->m));
	}

	public function det()
	{
		$L = new LUDecomposition($this);
		return $L->det();
	}

	public function mprint($A, $format = '%01.2f', $width = 2)
	{
		$m = count($A);
		$n = count($A[0]);
		$spacing = str_repeat('&nbsp;', $width);

		for ($i = 0; $i < $m; ++$i) {
			for ($j = 0; $j < $n; ++$j) {
				$formatted = sprintf($format, $A[$i][$j]);
				echo $formatted . $spacing;
			}

			echo '<br />';
		}
	}

	public function toHTML($width = 2)
	{
		print('<table style="background-color:#eee;">');

		for ($i = 0; $i < $this->m; ++$i) {
			print('<tr>');

			for ($j = 0; $j < $this->n; ++$j) {
				print('<td style="background-color:#fff;border:1px solid #000;padding:2px;text-align:center;vertical-align:middle;">' . $this->A[$i][$j] . '</td>');
			}

			print('</tr>');
		}

		print('</table>');
	}
}

define('RAND_MAX', mt_getrandmax());
define('RAND_MIN', 0);

if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
	PHPExcel_Autoloader::Register();
	PHPExcel_Shared_ZipStreamWrapper::register();

	if (ini_get('mbstring.func_overload') & 2) {
		throw new Exception('Multibyte function overloading in PHP must be disabled for string functions (2).');
	}
}

require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/JAMA/utils/Error.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/JAMA/utils/Maths.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/JAMA/CholeskyDecomposition.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/JAMA/LUDecomposition.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/JAMA/QRDecomposition.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/JAMA/EigenvalueDecomposition.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/JAMA/SingularValueDecomposition.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/String.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Calculation/Functions.php';

?>
