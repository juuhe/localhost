<?php

class PHPExcel_Calculation_Functions
{
	const COMPATIBILITY_EXCEL = 'Excel';
	const COMPATIBILITY_GNUMERIC = 'Gnumeric';
	const COMPATIBILITY_OPENOFFICE = 'OpenOfficeCalc';
	const RETURNDATE_PHP_NUMERIC = 'P';
	const RETURNDATE_PHP_OBJECT = 'O';
	const RETURNDATE_EXCEL = 'E';

	/**
	 *	Compatibility mode to use for error checking and responses
	 *
	 *	@access	private
	 *	@var string
	 */
	static private $compatibilityMode = self::COMPATIBILITY_EXCEL;
	/**
	 *	Data Type to use when returning date values
	 *
	 *	@access	private
	 *	@var string
	 */
	static private $ReturnDateType = self::RETURNDATE_EXCEL;
	/**
	 *	List of error codes
	 *
	 *	@access	private
	 *	@var array
	 */
	static private $_errorCodes = array('null' => '#NULL!', 'divisionbyzero' => '#DIV/0!', 'value' => '#VALUE!', 'reference' => '#REF!', 'name' => '#NAME?', 'num' => '#NUM!', 'na' => '#N/A', 'gettingdata' => '#GETTING_DATA');
	static private $_logBetaCache_p = 0;
	static private $_logBetaCache_q = 0;
	static private $_logBetaCache_result = 0;
	/**
	 * logGamma function
	 *
	 * @version 1.1
	 * @author Jaco van Kooten
	 *
	 * Original author was Jaco van Kooten. Ported to PHP by Paul Meagher.
	 *
	 * The natural logarithm of the gamma function. <br />
	 * Based on public domain NETLIB (Fortran) code by W. J. Cody and L. Stoltz <br />
	 * Applied Mathematics Division <br />
	 * Argonne National Laboratory <br />
	 * Argonne, IL 60439 <br />
	 * <p>
	 * References:
	 * <ol>
	 * <li>W. J. Cody and K. E. Hillstrom, 'Chebyshev Approximations for the Natural
	 *	 Logarithm of the Gamma Function,' Math. Comp. 21, 1967, pp. 198-203.</li>
	 * <li>K. E. Hillstrom, ANL/AMD Program ANLC366S, DGAMMA/DLGAMA, May, 1969.</li>
	 * <li>Hart, Et. Al., Computer Approximations, Wiley and sons, New York, 1968.</li>
	 * </ol>
	 * </p>
	 * <p>
	 * From the original documentation:
	 * </p>
	 * <p>
	 * This routine calculates the LOG(GAMMA) function for a positive real argument X.
	 * Computation is based on an algorithm outlined in references 1 and 2.
	 * The program uses rational functions that theoretically approximate LOG(GAMMA)
	 * to at least 18 significant decimal digits. The approximation for X > 12 is from
	 * reference 3, while approximations for X < 12.0 are similar to those in reference
	 * 1, but are unpublished. The accuracy achieved depends on the arithmetic system,
	 * the compiler, the intrinsic functions, and proper selection of the
	 * machine-dependent constants.
	 * </p>
	 * <p>
	 * Error returns: <br />
	 * The program returns the value XINF for X .LE. 0.0 or when overflow would occur.
	 * The computation is believed to be free of underflow and overflow.
	 * </p>
	 * @return MAX_VALUE for x < 0.0 or when overflow would occur, i.e. x > 2.55E305
	 */
	static private $_logGammaCache_result = 0;
	static private $_logGammaCache_x = 0;
	static private $_invalidChars;
	static private $_conversionUnits = array(
		'g'     => array('Group' => 'Mass', 'Unit Name' => 'Gram', 'AllowPrefix' => true),
		'sg'    => array('Group' => 'Mass', 'Unit Name' => 'Slug', 'AllowPrefix' => false),
		'lbm'   => array('Group' => 'Mass', 'Unit Name' => 'Pound mass (avoirdupois)', 'AllowPrefix' => false),
		'u'     => array('Group' => 'Mass', 'Unit Name' => 'U (atomic mass unit)', 'AllowPrefix' => true),
		'ozm'   => array('Group' => 'Mass', 'Unit Name' => 'Ounce mass (avoirdupois)', 'AllowPrefix' => false),
		'm'     => array('Group' => 'Distance', 'Unit Name' => 'Meter', 'AllowPrefix' => true),
		'mi'    => array('Group' => 'Distance', 'Unit Name' => 'Statute mile', 'AllowPrefix' => false),
		'Nmi'   => array('Group' => 'Distance', 'Unit Name' => 'Nautical mile', 'AllowPrefix' => false),
		'in'    => array('Group' => 'Distance', 'Unit Name' => 'Inch', 'AllowPrefix' => false),
		'ft'    => array('Group' => 'Distance', 'Unit Name' => 'Foot', 'AllowPrefix' => false),
		'yd'    => array('Group' => 'Distance', 'Unit Name' => 'Yard', 'AllowPrefix' => false),
		'ang'   => array('Group' => 'Distance', 'Unit Name' => 'Angstrom', 'AllowPrefix' => true),
		'Pica'  => array('Group' => 'Distance', 'Unit Name' => 'Pica (1/72 in)', 'AllowPrefix' => false),
		'yr'    => array('Group' => 'Time', 'Unit Name' => 'Year', 'AllowPrefix' => false),
		'day'   => array('Group' => 'Time', 'Unit Name' => 'Day', 'AllowPrefix' => false),
		'hr'    => array('Group' => 'Time', 'Unit Name' => 'Hour', 'AllowPrefix' => false),
		'mn'    => array('Group' => 'Time', 'Unit Name' => 'Minute', 'AllowPrefix' => false),
		'sec'   => array('Group' => 'Time', 'Unit Name' => 'Second', 'AllowPrefix' => true),
		'Pa'    => array('Group' => 'Pressure', 'Unit Name' => 'Pascal', 'AllowPrefix' => true),
		'p'     => array('Group' => 'Pressure', 'Unit Name' => 'Pascal', 'AllowPrefix' => true),
		'atm'   => array('Group' => 'Pressure', 'Unit Name' => 'Atmosphere', 'AllowPrefix' => true),
		'at'    => array('Group' => 'Pressure', 'Unit Name' => 'Atmosphere', 'AllowPrefix' => true),
		'mmHg'  => array('Group' => 'Pressure', 'Unit Name' => 'mm of Mercury', 'AllowPrefix' => true),
		'N'     => array('Group' => 'Force', 'Unit Name' => 'Newton', 'AllowPrefix' => true),
		'dyn'   => array('Group' => 'Force', 'Unit Name' => 'Dyne', 'AllowPrefix' => true),
		'dy'    => array('Group' => 'Force', 'Unit Name' => 'Dyne', 'AllowPrefix' => true),
		'lbf'   => array('Group' => 'Force', 'Unit Name' => 'Pound force', 'AllowPrefix' => false),
		'J'     => array('Group' => 'Energy', 'Unit Name' => 'Joule', 'AllowPrefix' => true),
		'e'     => array('Group' => 'Energy', 'Unit Name' => 'Erg', 'AllowPrefix' => true),
		'c'     => array('Group' => 'Energy', 'Unit Name' => 'Thermodynamic calorie', 'AllowPrefix' => true),
		'cal'   => array('Group' => 'Energy', 'Unit Name' => 'IT calorie', 'AllowPrefix' => true),
		'eV'    => array('Group' => 'Energy', 'Unit Name' => 'Electron volt', 'AllowPrefix' => true),
		'ev'    => array('Group' => 'Energy', 'Unit Name' => 'Electron volt', 'AllowPrefix' => true),
		'HPh'   => array('Group' => 'Energy', 'Unit Name' => 'Horsepower-hour', 'AllowPrefix' => false),
		'hh'    => array('Group' => 'Energy', 'Unit Name' => 'Horsepower-hour', 'AllowPrefix' => false),
		'Wh'    => array('Group' => 'Energy', 'Unit Name' => 'Watt-hour', 'AllowPrefix' => true),
		'wh'    => array('Group' => 'Energy', 'Unit Name' => 'Watt-hour', 'AllowPrefix' => true),
		'flb'   => array('Group' => 'Energy', 'Unit Name' => 'Foot-pound', 'AllowPrefix' => false),
		'BTU'   => array('Group' => 'Energy', 'Unit Name' => 'BTU', 'AllowPrefix' => false),
		'btu'   => array('Group' => 'Energy', 'Unit Name' => 'BTU', 'AllowPrefix' => false),
		'HP'    => array('Group' => 'Power', 'Unit Name' => 'Horsepower', 'AllowPrefix' => false),
		'h'     => array('Group' => 'Power', 'Unit Name' => 'Horsepower', 'AllowPrefix' => false),
		'W'     => array('Group' => 'Power', 'Unit Name' => 'Watt', 'AllowPrefix' => true),
		'w'     => array('Group' => 'Power', 'Unit Name' => 'Watt', 'AllowPrefix' => true),
		'T'     => array('Group' => 'Magnetism', 'Unit Name' => 'Tesla', 'AllowPrefix' => true),
		'ga'    => array('Group' => 'Magnetism', 'Unit Name' => 'Gauss', 'AllowPrefix' => true),
		'C'     => array('Group' => 'Temperature', 'Unit Name' => 'Celsius', 'AllowPrefix' => false),
		'cel'   => array('Group' => 'Temperature', 'Unit Name' => 'Celsius', 'AllowPrefix' => false),
		'F'     => array('Group' => 'Temperature', 'Unit Name' => 'Fahrenheit', 'AllowPrefix' => false),
		'fah'   => array('Group' => 'Temperature', 'Unit Name' => 'Fahrenheit', 'AllowPrefix' => false),
		'K'     => array('Group' => 'Temperature', 'Unit Name' => 'Kelvin', 'AllowPrefix' => false),
		'kel'   => array('Group' => 'Temperature', 'Unit Name' => 'Kelvin', 'AllowPrefix' => false),
		'tsp'   => array('Group' => 'Liquid', 'Unit Name' => 'Teaspoon', 'AllowPrefix' => false),
		'tbs'   => array('Group' => 'Liquid', 'Unit Name' => 'Tablespoon', 'AllowPrefix' => false),
		'oz'    => array('Group' => 'Liquid', 'Unit Name' => 'Fluid Ounce', 'AllowPrefix' => false),
		'cup'   => array('Group' => 'Liquid', 'Unit Name' => 'Cup', 'AllowPrefix' => false),
		'pt'    => array('Group' => 'Liquid', 'Unit Name' => 'U.S. Pint', 'AllowPrefix' => false),
		'us_pt' => array('Group' => 'Liquid', 'Unit Name' => 'U.S. Pint', 'AllowPrefix' => false),
		'uk_pt' => array('Group' => 'Liquid', 'Unit Name' => 'U.K. Pint', 'AllowPrefix' => false),
		'qt'    => array('Group' => 'Liquid', 'Unit Name' => 'Quart', 'AllowPrefix' => false),
		'gal'   => array('Group' => 'Liquid', 'Unit Name' => 'Gallon', 'AllowPrefix' => false),
		'l'     => array('Group' => 'Liquid', 'Unit Name' => 'Litre', 'AllowPrefix' => true),
		'lt'    => array('Group' => 'Liquid', 'Unit Name' => 'Litre', 'AllowPrefix' => true)
		);
	static private $_conversionMultipliers = array(
		'Y' => array('multiplier' => 9.9999999999999998E+23, 'name' => 'yotta'),
		'Z' => array('multiplier' => 1.0E+21, 'name' => 'zetta'),
		'E' => array('multiplier' => 1.0E+18, 'name' => 'exa'),
		'P' => array('multiplier' => 1000000000000000, 'name' => 'peta'),
		'T' => array('multiplier' => 1000000000000, 'name' => 'tera'),
		'G' => array('multiplier' => 1000000000, 'name' => 'giga'),
		'M' => array('multiplier' => 1000000, 'name' => 'mega'),
		'k' => array('multiplier' => 1000, 'name' => 'kilo'),
		'h' => array('multiplier' => 100, 'name' => 'hecto'),
		'e' => array('multiplier' => 10, 'name' => 'deka'),
		'd' => array('multiplier' => 0.10000000000000001, 'name' => 'deci'),
		'c' => array('multiplier' => 0.01, 'name' => 'centi'),
		'm' => array('multiplier' => 0.001, 'name' => 'milli'),
		'u' => array('multiplier' => 9.9999999999999995E-7, 'name' => 'micro'),
		'n' => array('multiplier' => 1.0000000000000001E-9, 'name' => 'nano'),
		'p' => array('multiplier' => 9.9999999999999998E-13, 'name' => 'pico'),
		'f' => array('multiplier' => 1.0000000000000001E-15, 'name' => 'femto'),
		'a' => array('multiplier' => 1.0000000000000001E-18, 'name' => 'atto'),
		'z' => array('multiplier' => 9.9999999999999991E-22, 'name' => 'zepto'),
		'y' => array('multiplier' => 9.9999999999999992E-25, 'name' => 'yocto')
		);
	static private $_unitConversions = array(
		'Mass'      => array(
			'g'   => array('g' => 1, 'sg' => 6.8522050005347796E-5, 'lbm' => 0.0022046229146913399, 'u' => 6.02217E+23, 'ozm' => 0.035273971800362701),
			'sg'  => array('g' => 14593.8424189287, 'sg' => 1, 'lbm' => 32.1739194101647, 'u' => 8.7886599999999995E+27, 'ozm' => 514.78278594422898),
			'lbm' => array('g' => 453.59230974881149, 'sg' => 0.031081074930649301, 'lbm' => 1, 'u' => 2.7316099999999999E+26, 'ozm' => 16.000002342940999),
			'u'   => array('g' => 1.6605310046046502E-24, 'sg' => 1.1378298853294999E-28, 'lbm' => 3.6608447033068398E-27, 'u' => 1, 'ozm' => 5.8573523830052397E-26),
			'ozm' => array('g' => 28.349515207973202, 'sg' => 0.0019425668987081101, 'lbm' => 0.062499990847888202, 'u' => 1.707256E+25, 'ozm' => 1)
			),
		'Distance'  => array(
			'm'    => array('m' => 1, 'mi' => 0.00062137119223733403, 'Nmi' => 0.00053995680345572401, 'in' => 39.370078740157503, 'ft' => 3.2808398950131199, 'yd' => 1.0936132979789099, 'ang' => 10000000000, 'Pica' => 2834.6456692911602),
			'mi'   => array('m' => 1609.3440000000001, 'mi' => 1, 'Nmi' => 0.86897624190064804, 'in' => 63360, 'ft' => 5280, 'yd' => 1760, 'ang' => 16093440000000, 'Pica' => 4561919.9999997104),
			'Nmi'  => array('m' => 1852, 'mi' => 1.15077944802354, 'Nmi' => 1, 'in' => 72913.385826771701, 'ft' => 6076.1154855642999, 'yd' => 2025.3718278569399, 'ang' => 18520000000000, 'Pica' => 5249763.7795272302),
			'in'   => array('m' => 0.025399999999999999, 'mi' => 1.57828282828283E-5, 'Nmi' => 1.37149028077754E-5, 'in' => 1, 'ft' => 0.083333333333333301, 'yd' => 0.027777777768664299, 'ang' => 254000000, 'Pica' => 71.999999999995495),
			'ft'   => array('m' => 0.30480000000000002, 'mi' => 0.00018939393939393899, 'Nmi' => 0.00016457883369330501, 'in' => 12, 'ft' => 1, 'yd' => 0.33333333322397202, 'ang' => 3048000000, 'Pica' => 863.999999999946),
			'yd'   => array('m' => 0.91440000030000002, 'mi' => 0.00056818181836823002, 'Nmi' => 0.000493736501241901, 'in' => 36.000000011810997, 'ft' => 3, 'yd' => 1, 'ang' => 9144000003, 'Pica' => 2592.0000008502302),
			'ang'  => array('m' => 1.0E-10, 'mi' => 6.2137119223733406E-14, 'Nmi' => 5.3995680345572402E-14, 'in' => 3.9370078740157498E-9, 'ft' => 3.28083989501312E-10, 'yd' => 1.09361329797891E-10, 'ang' => 1, 'Pica' => 2.8346456692911602E-7),
			'Pica' => array('m' => 0.00035277777777779998, 'mi' => 2.1920594837262899E-7, 'Nmi' => 1.9048476121911401E-7, 'in' => 0.013888888888889801, 'ft' => 0.00115740740740748, 'yd' => 0.00038580246900925101, 'ang' => 3527777.7777780001, 'Pica' => 1)
			),
		'Time'      => array(
			'yr'  => array('yr' => 1, 'day' => 365.25, 'hr' => 8766, 'mn' => 525960, 'sec' => 31557600),
			'day' => array('yr' => 0.0027378507871321, 'day' => 1, 'hr' => 24, 'mn' => 1440, 'sec' => 86400),
			'hr'  => array('yr' => 0.000114077116130504, 'day' => 0.041666666666666699, 'hr' => 1, 'mn' => 60, 'sec' => 3600),
			'mn'  => array('yr' => 1.9012852688417401E-6, 'day' => 0.00069444444444444404, 'hr' => 0.016666666666666701, 'mn' => 1, 'sec' => 60),
			'sec' => array('yr' => 3.1688087814028901E-8, 'day' => 1.1574074074074101E-5, 'hr' => 0.00027777777777777799, 'mn' => 0.016666666666666701, 'sec' => 1)
			),
		'Pressure'  => array(
			'Pa'   => array('Pa' => 1, 'p' => 1, 'atm' => 9.86923299998193E-6, 'at' => 9.86923299998193E-6, 'mmHg' => 0.0075006170799862704),
			'p'    => array('Pa' => 1, 'p' => 1, 'atm' => 9.86923299998193E-6, 'at' => 9.86923299998193E-6, 'mmHg' => 0.0075006170799862704),
			'atm'  => array('Pa' => 101324.996583, 'p' => 101324.996583, 'atm' => 1, 'at' => 1, 'mmHg' => 760),
			'at'   => array('Pa' => 101324.996583, 'p' => 101324.996583, 'atm' => 1, 'at' => 1, 'mmHg' => 760),
			'mmHg' => array('Pa' => 133.32236392499999, 'p' => 133.32236392499999, 'atm' => 0.0013157894736842101, 'at' => 0.0013157894736842101, 'mmHg' => 1)
			),
		'Force'     => array(
			'N'   => array('N' => 1, 'dyn' => 100000, 'dy' => 100000, 'lbf' => 0.224808923655339),
			'dyn' => array('N' => 1.0000000000000001E-5, 'dyn' => 1, 'dy' => 1, 'lbf' => 2.24808923655339E-6),
			'dy'  => array('N' => 1.0000000000000001E-5, 'dyn' => 1, 'dy' => 1, 'lbf' => 2.24808923655339E-6),
			'lbf' => array('N' => 4.4482220000000003, 'dyn' => 444822.20000000001, 'dy' => 444822.20000000001, 'lbf' => 1)
			),
		'Energy'    => array(
			'J'   => array('J' => 1, 'e' => 9999995.1934323106, 'c' => 0.23900624947346699, 'cal' => 0.23884619064201701, 'eV' => 6.241457E+18, 'ev' => 6.241457E+18, 'HPh' => 3.72506430801E-7, 'hh' => 3.72506430801E-7, 'Wh' => 0.00027777791623871098, 'wh' => 0.00027777791623871098, 'flb' => 23.730422219265101, 'BTU' => 0.000947815067349015, 'btu' => 0.000947815067349015),
			'e'   => array('J' => 1.000000480657E-7, 'e' => 1, 'c' => 2.3900636435349401E-8, 'cal' => 2.38846305445111E-8, 'eV' => 624146000000, 'ev' => 624146000000, 'HPh' => 3.7250660984882402E-14, 'hh' => 3.7250660984882402E-14, 'Wh' => 2.7777804975461099E-11, 'wh' => 2.7777804975461099E-11, 'flb' => 2.3730433625458602E-6, 'BTU' => 9.4781552292296198E-11, 'btu' => 9.4781552292296198E-11),
			'c'   => array('J' => 4.18399101363672, 'e' => 41839890.025731198, 'c' => 1, 'cal' => 0.999330315287563, 'eV' => 2.61142E+19, 'ev' => 2.61142E+19, 'HPh' => 1.5585635589932699E-6, 'hh' => 1.5585635589932699E-6, 'Wh' => 0.0011622203053295, 'wh' => 0.0011622203053295, 'flb' => 99.287873315210206, 'BTU' => 0.0039656497243777599, 'btu' => 0.0039656497243777599),
			'cal' => array('J' => 4.1867948461392901, 'e' => 41867928.337280102, 'c' => 1.0006701334905901, 'cal' => 1, 'eV' => 2.61317E+19, 'ev' => 2.61317E+19, 'HPh' => 1.55960800463137E-6, 'hh' => 1.55960800463137E-6, 'Wh' => 0.00116299914807955, 'wh' => 0.00116299914807955, 'flb' => 99.354409444328297, 'BTU' => 0.0039683072390700198, 'btu' => 0.0039683072390700198),
			'eV'  => array('J' => 1.60219000146921E-19, 'e' => 1.6021892313657401E-12, 'c' => 3.8293342319504298E-20, 'cal' => 3.8267697853564798E-20, 'eV' => 1, 'ev' => 1, 'HPh' => 5.9682607891234406E-26, 'hh' => 5.9682607891234406E-26, 'Wh' => 4.45053000026614E-23, 'wh' => 4.45053000026614E-23, 'flb' => 3.8020645210349202E-18, 'BTU' => 1.51857982414846E-22, 'btu' => 1.51857982414846E-22),
			'ev'  => array('J' => 1.60219000146921E-19, 'e' => 1.6021892313657401E-12, 'c' => 3.8293342319504298E-20, 'cal' => 3.8267697853564798E-20, 'eV' => 1, 'ev' => 1, 'HPh' => 5.9682607891234406E-26, 'hh' => 5.9682607891234406E-26, 'Wh' => 4.45053000026614E-23, 'wh' => 4.45053000026614E-23, 'flb' => 3.8020645210349202E-18, 'BTU' => 1.51857982414846E-22, 'btu' => 1.51857982414846E-22),
			'HPh' => array('J' => 2684517.4131617001, 'e' => 26845161228302.398, 'c' => 641616.438565991, 'cal' => 641186.75784583495, 'eV' => 1.6755300000000001E+25, 'ev' => 1.6755300000000001E+25, 'HPh' => 1, 'hh' => 1, 'Wh' => 745.69965313459295, 'wh' => 745.69965313459295, 'flb' => 63704731.669296399, 'BTU' => 2544.4260527554602, 'btu' => 2544.4260527554602),
			'hh'  => array('J' => 2684517.4131617001, 'e' => 26845161228302.398, 'c' => 641616.438565991, 'cal' => 641186.75784583495, 'eV' => 1.6755300000000001E+25, 'ev' => 1.6755300000000001E+25, 'HPh' => 1, 'hh' => 1, 'Wh' => 745.69965313459295, 'wh' => 745.69965313459295, 'flb' => 63704731.669296399, 'BTU' => 2544.4260527554602, 'btu' => 2544.4260527554602),
			'Wh'  => array('J' => 3599.9982055472001, 'e' => 35999964751.836899, 'c' => 860.42206921904597, 'cal' => 859.84585771304603, 'eV' => 2.2469234000000002E+22, 'ev' => 2.2469234000000002E+22, 'HPh' => 0.0013410224824383899, 'hh' => 0.0013410224824383899, 'Wh' => 1, 'wh' => 1, 'flb' => 85429.477406231599, 'BTU' => 3.41213254164705, 'btu' => 3.41213254164705),
			'wh'  => array('J' => 3599.9982055472001, 'e' => 35999964751.836899, 'c' => 860.42206921904597, 'cal' => 859.84585771304603, 'eV' => 2.2469234000000002E+22, 'ev' => 2.2469234000000002E+22, 'HPh' => 0.0013410224824383899, 'hh' => 0.0013410224824383899, 'Wh' => 1, 'wh' => 1, 'flb' => 85429.477406231599, 'BTU' => 3.41213254164705, 'btu' => 3.41213254164705),
			'flb' => array('J' => 0.042140000323642401, 'e' => 421399.80068766, 'c' => 0.0100717234301644, 'cal' => 0.0100649785509554, 'eV' => 2.63015E+17, 'ev' => 2.63015E+17, 'HPh' => 1.5697421114513001E-8, 'hh' => 1.5697421114513001E-8, 'Wh' => 1.17055614802E-5, 'wh' => 1.17055614802E-5, 'flb' => 1, 'BTU' => 3.9940927244840602E-5, 'btu' => 3.9940927244840602E-5),
			'BTU' => array('J' => 1055.0581378674899, 'e' => 10550576307.466499, 'c' => 252.16548850816801, 'cal' => 251.99661713551001, 'eV' => 6.5851000000000001E+21, 'ev' => 6.5851000000000001E+21, 'HPh' => 0.00039301594122456799, 'hh' => 0.00039301594122456799, 'Wh' => 0.29307185104752598, 'wh' => 0.29307185104752598, 'flb' => 25036.975077467101, 'BTU' => 1, 'btu' => 1),
			'btu' => array('J' => 1055.0581378674899, 'e' => 10550576307.466499, 'c' => 252.16548850816801, 'cal' => 251.99661713551001, 'eV' => 6.5851000000000001E+21, 'ev' => 6.5851000000000001E+21, 'HPh' => 0.00039301594122456799, 'hh' => 0.00039301594122456799, 'Wh' => 0.29307185104752598, 'wh' => 0.29307185104752598, 'flb' => 25036.975077467101, 'BTU' => 1, 'btu' => 1)
			),
		'Power'     => array(
			'HP' => array('HP' => 1, 'h' => 1, 'W' => 745.70100000000002, 'w' => 745.70100000000002),
			'h'  => array('HP' => 1, 'h' => 1, 'W' => 745.70100000000002, 'w' => 745.70100000000002),
			'W'  => array('HP' => 0.0013410200603190801, 'h' => 0.0013410200603190801, 'W' => 1, 'w' => 1),
			'w'  => array('HP' => 0.0013410200603190801, 'h' => 0.0013410200603190801, 'W' => 1, 'w' => 1)
			),
		'Magnetism' => array(
			'T'  => array('T' => 1, 'ga' => 10000),
			'ga' => array('T' => 0.0001, 'ga' => 1)
			),
		'Liquid'    => array(
			'tsp'   => array('tsp' => 1, 'tbs' => 0.33333333333333298, 'oz' => 0.16666666666666699, 'cup' => 0.020833333333333301, 'pt' => 0.010416666666666701, 'us_pt' => 0.010416666666666701, 'uk_pt' => 0.0086755851682195993, 'qt' => 0.0052083333333333296, 'gal' => 0.00130208333333333, 'l' => 0.0049299940840070999, 'lt' => 0.0049299940840070999),
			'tbs'   => array('tsp' => 3, 'tbs' => 1, 'oz' => 0.5, 'cup' => 0.0625, 'pt' => 0.03125, 'us_pt' => 0.03125, 'uk_pt' => 0.0260267555046588, 'qt' => 0.015625, 'gal' => 0.00390625, 'l' => 0.0147899822520213, 'lt' => 0.0147899822520213),
			'oz'    => array('tsp' => 6, 'tbs' => 2, 'oz' => 1, 'cup' => 0.125, 'pt' => 0.0625, 'us_pt' => 0.0625, 'uk_pt' => 0.052053511009317599, 'qt' => 0.03125, 'gal' => 0.0078125, 'l' => 0.0295799645040426, 'lt' => 0.0295799645040426),
			'cup'   => array('tsp' => 48, 'tbs' => 16, 'oz' => 8, 'cup' => 1, 'pt' => 0.5, 'us_pt' => 0.5, 'uk_pt' => 0.41642808807454101, 'qt' => 0.25, 'gal' => 0.0625, 'l' => 0.23663971603234099, 'lt' => 0.23663971603234099),
			'pt'    => array('tsp' => 96, 'tbs' => 32, 'oz' => 16, 'cup' => 2, 'pt' => 1, 'us_pt' => 1, 'uk_pt' => 0.83285617614908103, 'qt' => 0.5, 'gal' => 0.125, 'l' => 0.47327943206468198, 'lt' => 0.47327943206468198),
			'us_pt' => array('tsp' => 96, 'tbs' => 32, 'oz' => 16, 'cup' => 2, 'pt' => 1, 'us_pt' => 1, 'uk_pt' => 0.83285617614908103, 'qt' => 0.5, 'gal' => 0.125, 'l' => 0.47327943206468198, 'lt' => 0.47327943206468198),
			'uk_pt' => array('tsp' => 115.26600000000001, 'tbs' => 38.421999999999997, 'oz' => 19.210999999999999, 'cup' => 2.4013749999999998, 'pt' => 1.2006874999999999, 'us_pt' => 1.2006874999999999, 'uk_pt' => 1, 'qt' => 0.60034374999999995, 'gal' => 0.15008593749999999, 'l' => 0.56826069808716195, 'lt' => 0.56826069808716195),
			'qt'    => array('tsp' => 192, 'tbs' => 64, 'oz' => 32, 'cup' => 4, 'pt' => 2, 'us_pt' => 2, 'uk_pt' => 1.6657123522981601, 'qt' => 1, 'gal' => 0.25, 'l' => 0.94655886412936296, 'lt' => 0.94655886412936296),
			'gal'   => array('tsp' => 768, 'tbs' => 256, 'oz' => 128, 'cup' => 16, 'pt' => 8, 'us_pt' => 8, 'uk_pt' => 6.66284940919265, 'qt' => 4, 'gal' => 1, 'l' => 3.7862354565174501, 'lt' => 3.7862354565174501),
			'l'     => array('tsp' => 202.84, 'tbs' => 67.613333333333301, 'oz' => 33.8066666666667, 'cup' => 4.2258333333333304, 'pt' => 2.1129166666666701, 'us_pt' => 2.1129166666666701, 'uk_pt' => 1.75975569552166, 'qt' => 1.0564583333333299, 'gal' => 0.26411458333333299, 'l' => 1, 'lt' => 1),
			'lt'    => array('tsp' => 202.84, 'tbs' => 67.613333333333301, 'oz' => 33.8066666666667, 'cup' => 4.2258333333333304, 'pt' => 2.1129166666666701, 'us_pt' => 2.1129166666666701, 'uk_pt' => 1.75975569552166, 'qt' => 1.0564583333333299, 'gal' => 0.26411458333333299, 'l' => 1, 'lt' => 1)
			)
		);
	static private $_two_sqrtpi = 1.1283791670955126;
	static private $_one_sqrtpi = 0.56418958354775628;

	static public function setCompatibilityMode($compatibilityMode)
	{
		if (($compatibilityMode == self::COMPATIBILITY_EXCEL) || ($compatibilityMode == self::COMPATIBILITY_GNUMERIC) || ($compatibilityMode == self::COMPATIBILITY_OPENOFFICE)) {
			self::$compatibilityMode = $compatibilityMode;
			return true;
		}

		return false;
	}

	static public function getCompatibilityMode()
	{
		return self::$compatibilityMode;
	}

	static public function setReturnDateType($returnDateType)
	{
		if (($returnDateType == self::RETURNDATE_PHP_NUMERIC) || ($returnDateType == self::RETURNDATE_PHP_OBJECT) || ($returnDateType == self::RETURNDATE_EXCEL)) {
			self::$ReturnDateType = $returnDateType;
			return true;
		}

		return false;
	}

	static public function getReturnDateType()
	{
		return self::$ReturnDateType;
	}

	static public function DUMMY()
	{
		return '#Not Yet Implemented';
	}

	static public function NA()
	{
		return self::$_errorCodes['na'];
	}

	static public function NaN()
	{
		return self::$_errorCodes['num'];
	}

	static public function NAME()
	{
		return self::$_errorCodes['name'];
	}

	static public function REF()
	{
		return self::$_errorCodes['reference'];
	}

	static public function VALUE()
	{
		return self::$_errorCodes['value'];
	}

	static private function isMatrixValue($idx)
	{
		return (substr_count($idx, '.') <= 1) || (0 < preg_match('/\\.[A-Z]/', $idx));
	}

	static private function isValue($idx)
	{
		return substr_count($idx, '.') == 0;
	}

	static private function isCellValue($idx)
	{
		return 1 < substr_count($idx, '.');
	}

	static public function LOGICAL_AND()
	{
		$returnValue = true;
		$aArgs = self::flattenArray(func_get_args());
		$argCount = 0;

		foreach ($aArgs as $arg) {
			if (is_bool($arg)) {
				$returnValue = $returnValue && $arg;
			}
			else {
				if (is_numeric($arg) && !is_string($arg)) {
					$returnValue = $returnValue && ($arg != 0);
				}
				else if (is_string($arg)) {
					$arg = strtoupper($arg);

					if ($arg == 'TRUE') {
						$arg = 1;
					}
					else if ($arg == 'FALSE') {
						$arg = 0;
					}
					else {
						return self::$_errorCodes['value'];
					}

					$returnValue = $returnValue && ($arg != 0);
				}
			}

			++$argCount;
		}

		if ($argCount == 0) {
			return self::$_errorCodes['value'];
		}

		return $returnValue;
	}

	static public function LOGICAL_OR()
	{
		$returnValue = false;
		$aArgs = self::flattenArray(func_get_args());
		$argCount = 0;

		foreach ($aArgs as $arg) {
			if (is_bool($arg)) {
				$returnValue = $returnValue || $arg;
			}
			else {
				if (is_numeric($arg) && !is_string($arg)) {
					$returnValue = $returnValue || ($arg != 0);
				}
				else if (is_string($arg)) {
					$arg = strtoupper($arg);

					if ($arg == 'TRUE') {
						$arg = 1;
					}
					else if ($arg == 'FALSE') {
						$arg = 0;
					}
					else {
						return self::$_errorCodes['value'];
					}

					$returnValue = $returnValue || ($arg != 0);
				}
			}

			++$argCount;
		}

		if ($argCount == 0) {
			return self::$_errorCodes['value'];
		}

		return $returnValue;
	}

	static public function LOGICAL_FALSE()
	{
		return false;
	}

	static public function LOGICAL_TRUE()
	{
		return true;
	}

	static public function LOGICAL_NOT($logical)
	{
		$logical = self::flattenSingleValue($logical);

		if (is_string($logical)) {
			$logical = strtoupper($logical);

			if ($logical == 'TRUE') {
				return false;
			}
			else if ($logical == 'FALSE') {
				return true;
			}
			else {
				return self::$_errorCodes['value'];
			}
		}

		return !$logical;
	}

	static public function STATEMENT_IF($condition = true, $returnIfTrue = 0, $returnIfFalse = false)
	{
		$condition = (is_null($condition) ? true : (bool) self::flattenSingleValue($condition));
		$returnIfTrue = (is_null($returnIfTrue) ? 0 : self::flattenSingleValue($returnIfTrue));
		$returnIfFalse = (is_null($returnIfFalse) ? false : self::flattenSingleValue($returnIfFalse));
		return $condition ? $returnIfTrue : $returnIfFalse;
	}

	static public function STATEMENT_IFERROR($testValue = '', $errorpart = '')
	{
		$testValue = (is_null($testValue) ? '' : self::flattenSingleValue($testValue));
		$errorpart = (is_null($errorpart) ? '' : self::flattenSingleValue($errorpart));
		return self::STATEMENT_IF(self::IS_ERROR($testValue), $errorpart, $testValue);
	}

	static public function HYPERLINK($linkURL = '', $displayName = NULL, PHPExcel_Cell $pCell = NULL)
	{
		$args = func_get_args();
		$pCell = array_pop($args);
		$linkURL = (is_null($linkURL) ? '' : self::flattenSingleValue($linkURL));
		$displayName = (is_null($displayName) ? '' : self::flattenSingleValue($displayName));
		if (!is_object($pCell) || (trim($linkURL) == '')) {
			return self::$_errorCodes['reference'];
		}

		if (is_object($displayName) || (trim($displayName) == '')) {
			$displayName = $linkURL;
		}

		$pCell->getHyperlink()->setUrl($linkURL);
		return $displayName;
	}

	static public function REVERSE_ATAN2($xCoordinate, $yCoordinate)
	{
		$xCoordinate = (double) self::flattenSingleValue($xCoordinate);
		$yCoordinate = (double) self::flattenSingleValue($yCoordinate);
		if (($xCoordinate == 0) && ($yCoordinate == 0)) {
			return self::$_errorCodes['divisionbyzero'];
		}

		return atan2($yCoordinate, $xCoordinate);
	}

	static public function LOG_BASE($number, $base = 10)
	{
		$number = self::flattenSingleValue($number);
		$base = (is_null($base) ? 10 : (double) self::flattenSingleValue($base));
		return log($number, $base);
	}

	static public function SUM()
	{
		$returnValue = 0;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) && !is_string($arg)) {
				$returnValue += $arg;
			}
		}

		return $returnValue;
	}

	static public function SUMSQ()
	{
		$returnValue = 0;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) && !is_string($arg)) {
				$returnValue += $arg * $arg;
			}
		}

		return $returnValue;
	}

	static public function PRODUCT()
	{
		$returnValue = NULL;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) && !is_string($arg)) {
				if (is_null($returnValue)) {
					$returnValue = $arg;
				}
				else {
					$returnValue *= $arg;
				}
			}
		}

		if (is_null($returnValue)) {
			return 0;
		}

		return $returnValue;
	}

	static public function QUOTIENT()
	{
		$returnValue = NULL;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) && !is_string($arg)) {
				if (is_null($returnValue)) {
					$returnValue = ($arg == 0 ? 0 : $arg);
				}
				else {
					if (($returnValue == 0) || ($arg == 0)) {
						$returnValue = 0;
					}
					else {
						$returnValue /= $arg;
					}
				}
			}
		}

		return intval($returnValue);
	}

	static public function MIN()
	{
		$returnValue = NULL;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) && !is_string($arg)) {
				if (is_null($returnValue) || ($arg < $returnValue)) {
					$returnValue = $arg;
				}
			}
		}

		if (is_null($returnValue)) {
			return 0;
		}

		return $returnValue;
	}

	static public function MINA()
	{
		$returnValue = NULL;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) || is_bool($arg) || (is_string($arg) && ($arg != ''))) {
				if (is_bool($arg)) {
					$arg = (int) $arg;
				}
				else if (is_string($arg)) {
					$arg = 0;
				}

				if (is_null($returnValue) || ($arg < $returnValue)) {
					$returnValue = $arg;
				}
			}
		}

		if (is_null($returnValue)) {
			return 0;
		}

		return $returnValue;
	}

	static public function MINIF($aArgs, $condition, $sumArgs = array())
	{
		$returnValue = NULL;
		$aArgs = self::flattenArray($aArgs);
		$sumArgs = self::flattenArray($sumArgs);

		if (count($sumArgs) == 0) {
			$sumArgs = $aArgs;
		}

		$condition = self::_ifCondition($condition);

		foreach ($aArgs as $key => $arg) {
			if (!is_numeric($arg)) {
				$arg = PHPExcel_Calculation::_wrapResult(strtoupper($arg));
			}

			$testCondition = '=' . $arg . $condition;

			if (PHPExcel_Calculation::getInstance()->_calculateFormulaValue($testCondition)) {
				if (is_null($returnValue) || ($arg < $returnValue)) {
					$returnValue = $arg;
				}
			}
		}

		return $returnValue;
	}

	static public function SMALL()
	{
		$aArgs = self::flattenArray(func_get_args());
		$entry = array_pop($aArgs);
		if (is_numeric($entry) && !is_string($entry)) {
			$mArgs = array();

			foreach ($aArgs as $arg) {
				if (is_numeric($arg) && !is_string($arg)) {
					$mArgs[] = $arg;
				}
			}

			$count = self::COUNT($mArgs);
			$entry = floor(--$entry);
			if (($entry < 0) || ($count <= $entry) || ($count == 0)) {
				return self::$_errorCodes['num'];
			}

			sort($mArgs);
			return $mArgs[$entry];
		}

		return self::$_errorCodes['value'];
	}

	static public function MAX()
	{
		$returnValue = NULL;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) && !is_string($arg)) {
				if (is_null($returnValue) || ($returnValue < $arg)) {
					$returnValue = $arg;
				}
			}
		}

		if (is_null($returnValue)) {
			return 0;
		}

		return $returnValue;
	}

	static public function MAXA()
	{
		$returnValue = NULL;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) || is_bool($arg) || (is_string($arg) && ($arg != ''))) {
				if (is_bool($arg)) {
					$arg = (int) $arg;
				}
				else if (is_string($arg)) {
					$arg = 0;
				}

				if (is_null($returnValue) || ($returnValue < $arg)) {
					$returnValue = $arg;
				}
			}
		}

		if (is_null($returnValue)) {
			return 0;
		}

		return $returnValue;
	}

	static private function _ifCondition($condition)
	{
		$condition = self::flattenSingleValue($condition);

		if (!in_array($condition[0], array('>', '<', '='))) {
			if (!is_numeric($condition)) {
				$condition = PHPExcel_Calculation::_wrapResult(strtoupper($condition));
			}

			return '=' . $condition;
		}
		else {
			preg_match('/([<>=]+)(.*)/', $condition, $matches);

			if (!is_numeric($operand)) {
				$operand = PHPExcel_Calculation::_wrapResult(strtoupper($operand));
			}

			return $operator . $operand;
		}
	}

	static public function MAXIF($aArgs, $condition, $sumArgs = array())
	{
		$returnValue = NULL;
		$aArgs = self::flattenArray($aArgs);
		$sumArgs = self::flattenArray($sumArgs);

		if (count($sumArgs) == 0) {
			$sumArgs = $aArgs;
		}

		$condition = self::_ifCondition($condition);

		foreach ($aArgs as $key => $arg) {
			if (!is_numeric($arg)) {
				$arg = PHPExcel_Calculation::_wrapResult(strtoupper($arg));
			}

			$testCondition = '=' . $arg . $condition;

			if (PHPExcel_Calculation::getInstance()->_calculateFormulaValue($testCondition)) {
				if (is_null($returnValue) || ($returnValue < $arg)) {
					$returnValue = $arg;
				}
			}
		}

		return $returnValue;
	}

	static public function LARGE()
	{
		$aArgs = self::flattenArray(func_get_args());
		$entry = floor(array_pop($aArgs));
		if (is_numeric($entry) && !is_string($entry)) {
			$mArgs = array();

			foreach ($aArgs as $arg) {
				if (is_numeric($arg) && !is_string($arg)) {
					$mArgs[] = $arg;
				}
			}

			$count = self::COUNT($mArgs);
			$entry = floor(--$entry);
			if (($entry < 0) || ($count <= $entry) || ($count == 0)) {
				return self::$_errorCodes['num'];
			}

			rsort($mArgs);
			return $mArgs[$entry];
		}

		return self::$_errorCodes['value'];
	}

	static public function PERCENTILE()
	{
		$aArgs = self::flattenArray(func_get_args());
		$entry = array_pop($aArgs);
		if (is_numeric($entry) && !is_string($entry)) {
			if (($entry < 0) || (1 < $entry)) {
				return self::$_errorCodes['num'];
			}

			$mArgs = array();

			foreach ($aArgs as $arg) {
				if (is_numeric($arg) && !is_string($arg)) {
					$mArgs[] = $arg;
				}
			}

			$mValueCount = count($mArgs);

			if (0 < $mValueCount) {
				sort($mArgs);
				$count = self::COUNT($mArgs);
				$index = $entry * ($count - 1);
				$iBase = floor($index);

				if ($index == $iBase) {
					return $mArgs[$index];
				}
				else {
					$iNext = $iBase + 1;
					$iProportion = $index - $iBase;
					return $mArgs[$iBase] + (($mArgs[$iNext] - $mArgs[$iBase]) * $iProportion);
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function QUARTILE()
	{
		$aArgs = self::flattenArray(func_get_args());
		$entry = floor(array_pop($aArgs));
		if (is_numeric($entry) && !is_string($entry)) {
			$entry /= 4;
			if (($entry < 0) || (1 < $entry)) {
				return self::$_errorCodes['num'];
			}

			return self::PERCENTILE($aArgs, $entry);
		}

		return self::$_errorCodes['value'];
	}

	static public function COUNT()
	{
		$returnValue = 0;
		$aArgs = self::flattenArrayIndexed(func_get_args());

		foreach ($aArgs as $k => $arg) {
			if (is_bool($arg) && (!self::isCellValue($k) || (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE))) {
				$arg = (int) $arg;
			}

			if (is_numeric($arg) && !is_string($arg)) {
				++$returnValue;
			}
		}

		return $returnValue;
	}

	static public function COUNTBLANK()
	{
		$returnValue = 0;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_null($arg) || (is_string($arg) && ($arg == ''))) {
				++$returnValue;
			}
		}

		return $returnValue;
	}

	static public function COUNTA()
	{
		$returnValue = 0;
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) || is_bool($arg) || (is_string($arg) && ($arg != ''))) {
				++$returnValue;
			}
		}

		return $returnValue;
	}

	static public function COUNTIF($aArgs, $condition)
	{
		$returnValue = 0;
		$aArgs = self::flattenArray($aArgs);
		$condition = self::_ifCondition($condition);

		foreach ($aArgs as $arg) {
			if (!is_numeric($arg)) {
				$arg = PHPExcel_Calculation::_wrapResult(strtoupper($arg));
			}

			$testCondition = '=' . $arg . $condition;

			if (PHPExcel_Calculation::getInstance()->_calculateFormulaValue($testCondition)) {
				++$returnValue;
			}
		}

		return $returnValue;
	}

	static public function SUMIF($aArgs, $condition, $sumArgs = array())
	{
		$returnValue = 0;
		$aArgs = self::flattenArray($aArgs);
		$sumArgs = self::flattenArray($sumArgs);

		if (count($sumArgs) == 0) {
			$sumArgs = $aArgs;
		}

		$condition = self::_ifCondition($condition);

		foreach ($aArgs as $key => $arg) {
			if (!is_numeric($arg)) {
				$arg = PHPExcel_Calculation::_wrapResult(strtoupper($arg));
			}

			$testCondition = '=' . $arg . $condition;

			if (PHPExcel_Calculation::getInstance()->_calculateFormulaValue($testCondition)) {
				$returnValue += $sumArgs[$key];
			}
		}

		return $returnValue;
	}

	static public function AVERAGE()
	{
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$returnValue = $aCount = 0;

		foreach ($aArgs as $k => $arg) {
			if (is_bool($arg) && (!self::isCellValue($k) || (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE))) {
				$arg = (int) $arg;
			}

			if (is_numeric($arg) && !is_string($arg)) {
				if (is_null($returnValue)) {
					$returnValue = $arg;
				}
				else {
					$returnValue += $arg;
				}

				++$aCount;
			}
		}

		if (0 < $aCount) {
			return $returnValue / $aCount;
		}
		else {
			return self::$_errorCodes['divisionbyzero'];
		}
	}

	static public function AVERAGEA()
	{
		$returnValue = NULL;
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$aCount = 0;

		foreach ($aArgs as $k => $arg) {
			if (is_bool($arg) && !self::isMatrixValue($k)) {
			}
			else {
				if (is_numeric($arg) || is_bool($arg) || (is_string($arg) && ($arg != ''))) {
					if (is_bool($arg)) {
						$arg = (int) $arg;
					}
					else if (is_string($arg)) {
						$arg = 0;
					}

					if (is_null($returnValue)) {
						$returnValue = $arg;
					}
					else {
						$returnValue += $arg;
					}

					++$aCount;
				}
			}
		}

		if (0 < $aCount) {
			return $returnValue / $aCount;
		}
		else {
			return self::$_errorCodes['divisionbyzero'];
		}
	}

	static public function AVERAGEIF($aArgs, $condition, $averageArgs = array())
	{
		$returnValue = 0;
		$aArgs = self::flattenArray($aArgs);
		$averageArgs = self::flattenArray($averageArgs);

		if (count($averageArgs) == 0) {
			$averageArgs = $aArgs;
		}

		$condition = self::_ifCondition($condition);
		$aCount = 0;

		foreach ($aArgs as $key => $arg) {
			if (!is_numeric($arg)) {
				$arg = PHPExcel_Calculation::_wrapResult(strtoupper($arg));
			}

			$testCondition = '=' . $arg . $condition;

			if (PHPExcel_Calculation::getInstance()->_calculateFormulaValue($testCondition)) {
				if (is_null($returnValue) || ($returnValue < $arg)) {
					$returnValue += $arg;
					++$aCount;
				}
			}
		}

		if (0 < $aCount) {
			return $returnValue / $aCount;
		}
		else {
			return self::$_errorCodes['divisionbyzero'];
		}
	}

	static public function MEDIAN()
	{
		$returnValue = self::$_errorCodes['num'];
		$mArgs = array();
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) && !is_string($arg)) {
				$mArgs[] = $arg;
			}
		}

		$mValueCount = count($mArgs);

		if (0 < $mValueCount) {
			sort($mArgs, SORT_NUMERIC);
			$mValueCount = $mValueCount / 2;

			if ($mValueCount == floor($mValueCount)) {
				$returnValue = ($mArgs[$mValueCount--] + $mArgs[$mValueCount]) / 2;
			}
			else {
				$mValueCount == floor($mValueCount);
				$returnValue = $mArgs[$mValueCount];
			}
		}

		return $returnValue;
	}

	static private function _modeCalc($data)
	{
		$frequencyArray = array();

		foreach ($data as $datum) {
			$found = false;

			foreach ($frequencyArray as $key => $value) {
				if ((string) $value['value'] == (string) $datum) {
					++$frequencyArray[$key]['frequency'];
					$found = true;
					break;
				}
			}

			if (!$found) {
				$frequencyArray[] = array('value' => $datum, 'frequency' => 1);
			}
		}

		foreach ($frequencyArray as $key => $value) {
			$frequencyList[$key] = $value['frequency'];
			$valueList[$key] = $value['value'];
		}

		array_multisort($frequencyList, SORT_DESC, $valueList, SORT_ASC, SORT_NUMERIC, $frequencyArray);

		if ($frequencyArray[0]['frequency'] == 1) {
			return self::NA();
		}

		return $frequencyArray[0]['value'];
	}

	static public function MODE()
	{
		$returnValue = self::NA();
		$aArgs = self::flattenArray(func_get_args());
		$mArgs = array();

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) && !is_string($arg)) {
				$mArgs[] = $arg;
			}
		}

		if (0 < count($mArgs)) {
			return self::_modeCalc($mArgs);
		}

		return $returnValue;
	}

	static public function DEVSQ()
	{
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$returnValue = NULL;
		$aMean = self::AVERAGE($aArgs);

		if ($aMean != self::$_errorCodes['divisionbyzero']) {
			$aCount = -1;

			foreach ($aArgs as $k => $arg) {
				if (is_bool($arg) && (!self::isCellValue($k) || (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE))) {
					$arg = (int) $arg;
				}

				if (is_numeric($arg) && !is_string($arg)) {
					if (is_null($returnValue)) {
						$returnValue = pow($arg - $aMean, 2);
					}
					else {
						$returnValue += pow($arg - $aMean, 2);
					}

					++$aCount;
				}
			}

			if (is_null($returnValue)) {
				return self::$_errorCodes['num'];
			}
			else {
				return $returnValue;
			}
		}

		return self::NA();
	}

	static public function AVEDEV()
	{
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$returnValue = NULL;
		$aMean = self::AVERAGE($aArgs);

		if ($aMean != self::$_errorCodes['divisionbyzero']) {
			$aCount = 0;

			foreach ($aArgs as $k => $arg) {
				if (is_bool($arg) && (!self::isCellValue($k) || (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE))) {
					$arg = (int) $arg;
				}

				if (is_numeric($arg) && !is_string($arg)) {
					if (is_null($returnValue)) {
						$returnValue = abs($arg - $aMean);
					}
					else {
						$returnValue += abs($arg - $aMean);
					}

					++$aCount;
				}
			}

			if ($aCount == 0) {
				return self::$_errorCodes['divisionbyzero'];
			}

			return $returnValue / $aCount;
		}

		return self::$_errorCodes['num'];
	}

	static public function GEOMEAN()
	{
		$aArgs = self::flattenArray(func_get_args());
		$aMean = self::PRODUCT($aArgs);
		if (is_numeric($aMean) && (0 < $aMean)) {
			$aCount = self::COUNT($aArgs);

			if (0 < self::MIN($aArgs)) {
				return pow($aMean, 1 / $aCount);
			}
		}

		return self::$_errorCodes['num'];
	}

	static public function HARMEAN()
	{
		$returnValue = self::NA();
		$aArgs = self::flattenArray(func_get_args());

		if (self::MIN($aArgs) < 0) {
			return self::$_errorCodes['num'];
		}

		$aCount = 0;

		foreach ($aArgs as $arg) {
			if (is_numeric($arg) && !is_string($arg)) {
				if ($arg <= 0) {
					return self::$_errorCodes['num'];
				}

				if (is_null($returnValue)) {
					$returnValue = 1 / $arg;
				}
				else {
					$returnValue += 1 / $arg;
				}

				++$aCount;
			}
		}

		if (0 < $aCount) {
			return 1 / $returnValue / $aCount;
		}
		else {
			return $returnValue;
		}
	}

	static public function TRIMMEAN()
	{
		$aArgs = self::flattenArray(func_get_args());
		$percent = array_pop($aArgs);
		if (is_numeric($percent) && !is_string($percent)) {
			if (($percent < 0) || (1 < $percent)) {
				return self::$_errorCodes['num'];
			}

			$mArgs = array();

			foreach ($aArgs as $arg) {
				if (is_numeric($arg) && !is_string($arg)) {
					$mArgs[] = $arg;
				}
			}

			$discard = floor((self::COUNT($mArgs) * $percent) / 2);
			sort($mArgs);

			for ($i = 0; $i < $discard; ++$i) {
				array_pop($mArgs);
				array_shift($mArgs);
			}

			return self::AVERAGE($mArgs);
		}

		return self::$_errorCodes['value'];
	}

	static public function STDEV()
	{
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$returnValue = NULL;
		$aMean = self::AVERAGE($aArgs);

		if (!is_null($aMean)) {
			$aCount = -1;

			foreach ($aArgs as $k => $arg) {
				if (is_bool($arg) && (!self::isCellValue($k) || (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE))) {
					$arg = (int) $arg;
				}

				if (is_numeric($arg) && !is_string($arg)) {
					if (is_null($returnValue)) {
						$returnValue = pow($arg - $aMean, 2);
					}
					else {
						$returnValue += pow($arg - $aMean, 2);
					}

					++$aCount;
				}
			}

			if ((0 < $aCount) && (0 < $returnValue)) {
				return sqrt($returnValue / $aCount);
			}
		}

		return self::$_errorCodes['divisionbyzero'];
	}

	static public function STDEVA()
	{
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$returnValue = NULL;
		$aMean = self::AVERAGEA($aArgs);

		if (!is_null($aMean)) {
			$aCount = -1;

			foreach ($aArgs as $k => $arg) {
				if (is_bool($arg) && !self::isMatrixValue($k)) {
				}
				else {
					if (is_numeric($arg) || is_bool($arg) || (is_string($arg) & ($arg != ''))) {
						if (is_bool($arg)) {
							$arg = (int) $arg;
						}
						else if (is_string($arg)) {
							$arg = 0;
						}

						if (is_null($returnValue)) {
							$returnValue = pow($arg - $aMean, 2);
						}
						else {
							$returnValue += pow($arg - $aMean, 2);
						}

						++$aCount;
					}
				}
			}

			if ((0 < $aCount) && (0 < $returnValue)) {
				return sqrt($returnValue / $aCount);
			}
		}

		return self::$_errorCodes['divisionbyzero'];
	}

	static public function STDEVP()
	{
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$returnValue = NULL;
		$aMean = self::AVERAGE($aArgs);

		if (!is_null($aMean)) {
			$aCount = 0;

			foreach ($aArgs as $k => $arg) {
				if (is_bool($arg) && (!self::isCellValue($k) || (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE))) {
					$arg = (int) $arg;
				}

				if (is_numeric($arg) && !is_string($arg)) {
					if (is_null($returnValue)) {
						$returnValue = pow($arg - $aMean, 2);
					}
					else {
						$returnValue += pow($arg - $aMean, 2);
					}

					++$aCount;
				}
			}

			if ((0 < $aCount) && (0 < $returnValue)) {
				return sqrt($returnValue / $aCount);
			}
		}

		return self::$_errorCodes['divisionbyzero'];
	}

	static public function STDEVPA()
	{
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$returnValue = NULL;
		$aMean = self::AVERAGEA($aArgs);

		if (!is_null($aMean)) {
			$aCount = 0;

			foreach ($aArgs as $k => $arg) {
				if (is_bool($arg) && !self::isMatrixValue($k)) {
				}
				else {
					if (is_numeric($arg) || is_bool($arg) || (is_string($arg) & ($arg != ''))) {
						if (is_bool($arg)) {
							$arg = (int) $arg;
						}
						else if (is_string($arg)) {
							$arg = 0;
						}

						if (is_null($returnValue)) {
							$returnValue = pow($arg - $aMean, 2);
						}
						else {
							$returnValue += pow($arg - $aMean, 2);
						}

						++$aCount;
					}
				}
			}

			if ((0 < $aCount) && (0 < $returnValue)) {
				return sqrt($returnValue / $aCount);
			}
		}

		return self::$_errorCodes['divisionbyzero'];
	}

	static public function VARFunc()
	{
		$returnValue = self::$_errorCodes['divisionbyzero'];
		$summerA = $summerB = 0;
		$aArgs = self::flattenArray(func_get_args());
		$aCount = 0;

		foreach ($aArgs as $arg) {
			if (is_bool($arg)) {
				$arg = (int) $arg;
			}

			if (is_numeric($arg) && !is_string($arg)) {
				$summerA += $arg * $arg;
				$summerB += $arg;
				++$aCount;
			}
		}

		if (1 < $aCount) {
			$summerA *= $aCount;
			$summerB *= $summerB;
			$returnValue = ($summerA - $summerB) / ($aCount * ($aCount - 1));
		}

		return $returnValue;
	}

	static public function VARA()
	{
		$returnValue = self::$_errorCodes['divisionbyzero'];
		$summerA = $summerB = 0;
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$aCount = 0;

		foreach ($aArgs as $k => $arg) {
			if (is_string($arg) && self::isValue($k)) {
				return self::$_errorCodes['value'];
			}
			else {
				if (is_string($arg) && !self::isMatrixValue($k)) {
				}
				else {
					if (is_numeric($arg) || is_bool($arg) || (is_string($arg) & ($arg != ''))) {
						if (is_bool($arg)) {
							$arg = (int) $arg;
						}
						else if (is_string($arg)) {
							$arg = 0;
						}

						$summerA += $arg * $arg;
						$summerB += $arg;
						++$aCount;
					}
				}
			}
		}

		if (1 < $aCount) {
			$summerA *= $aCount;
			$summerB *= $summerB;
			$returnValue = ($summerA - $summerB) / ($aCount * ($aCount - 1));
		}

		return $returnValue;
	}

	static public function VARP()
	{
		$returnValue = self::$_errorCodes['divisionbyzero'];
		$summerA = $summerB = 0;
		$aArgs = self::flattenArray(func_get_args());
		$aCount = 0;

		foreach ($aArgs as $arg) {
			if (is_bool($arg)) {
				$arg = (int) $arg;
			}

			if (is_numeric($arg) && !is_string($arg)) {
				$summerA += $arg * $arg;
				$summerB += $arg;
				++$aCount;
			}
		}

		if (0 < $aCount) {
			$summerA *= $aCount;
			$summerB *= $summerB;
			$returnValue = ($summerA - $summerB) / ($aCount * $aCount);
		}

		return $returnValue;
	}

	static public function VARPA()
	{
		$returnValue = self::$_errorCodes['divisionbyzero'];
		$summerA = $summerB = 0;
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$aCount = 0;

		foreach ($aArgs as $k => $arg) {
			if (is_string($arg) && self::isValue($k)) {
				return self::$_errorCodes['value'];
			}
			else {
				if (is_string($arg) && !self::isMatrixValue($k)) {
				}
				else {
					if (is_numeric($arg) || is_bool($arg) || (is_string($arg) & ($arg != ''))) {
						if (is_bool($arg)) {
							$arg = (int) $arg;
						}
						else if (is_string($arg)) {
							$arg = 0;
						}

						$summerA += $arg * $arg;
						$summerB += $arg;
						++$aCount;
					}
				}
			}
		}

		if (0 < $aCount) {
			$summerA *= $aCount;
			$summerB *= $summerB;
			$returnValue = ($summerA - $summerB) / ($aCount * $aCount);
		}

		return $returnValue;
	}

	static public function RANK($value, $valueSet, $order = 0)
	{
		$value = self::flattenSingleValue($value);
		$valueSet = self::flattenArray($valueSet);
		$order = (is_null($order) ? 0 : (int) self::flattenSingleValue($order));

		foreach ($valueSet as $key => $valueEntry) {
			if (!is_numeric($valueEntry)) {
				unset($valueSet[$key]);
			}
		}

		if ($order == 0) {
			rsort($valueSet, SORT_NUMERIC);
		}
		else {
			sort($valueSet, SORT_NUMERIC);
		}

		$pos = array_search($value, $valueSet);

		if ($pos === false) {
			return self::$_errorCodes['na'];
		}

		return ++$pos;
	}

	static public function PERCENTRANK($valueSet, $value, $significance = 3)
	{
		$valueSet = self::flattenArray($valueSet);
		$value = self::flattenSingleValue($value);
		$significance = (is_null($significance) ? 3 : (int) self::flattenSingleValue($significance));

		foreach ($valueSet as $key => $valueEntry) {
			if (!is_numeric($valueEntry)) {
				unset($valueSet[$key]);
			}
		}

		sort($valueSet, SORT_NUMERIC);
		$valueCount = count($valueSet);

		if ($valueCount == 0) {
			return self::$_errorCodes['num'];
		}

		$valueAdjustor = $valueCount - 1;
		if (($value < $valueSet[0]) || ($valueSet[$valueAdjustor] < $value)) {
			return self::$_errorCodes['na'];
		}

		$pos = array_search($value, $valueSet);

		if ($pos === false) {
			$pos = 0;
			$testValue = $valueSet[0];

			while ($testValue < $value) {
				$testValue = $valueSet[++$pos];
			}

			--$pos;
			$pos += ($value - $valueSet[$pos]) / ($testValue - $valueSet[$pos]);
		}

		return round($pos / $valueAdjustor, $significance);
	}

	static private function _checkTrendArrays(&$array1, &$array2)
	{
		if (!is_array($array1)) {
			$array1 = array($array1);
		}

		if (!is_array($array2)) {
			$array2 = array($array2);
		}

		$array1 = self::flattenArray($array1);
		$array2 = self::flattenArray($array2);

		foreach ($array1 as $key => $value) {
			if (is_bool($value) || is_string($value) || is_null($value)) {
				unset($array1[$key]);
				unset($array2[$key]);
			}
		}

		foreach ($array2 as $key => $value) {
			if (is_bool($value) || is_string($value) || is_null($value)) {
				unset($array1[$key]);
				unset($array2[$key]);
			}
		}

		$array1 = array_merge($array1);
		$array2 = array_merge($array2);
		return true;
	}

	static public function INTERCEPT($yValues, $xValues)
	{
		if (!self::_checkTrendArrays($yValues, $xValues)) {
			return self::$_errorCodes['value'];
		}

		$yValueCount = count($yValues);
		$xValueCount = count($xValues);
		if (($yValueCount == 0) || ($yValueCount != $xValueCount)) {
			return self::$_errorCodes['na'];
		}
		else if ($yValueCount == 1) {
			return self::$_errorCodes['divisionbyzero'];
		}

		$bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
		return $bestFitLinear->getIntersect();
	}

	static public function RSQ($yValues, $xValues)
	{
		if (!self::_checkTrendArrays($yValues, $xValues)) {
			return self::$_errorCodes['value'];
		}

		$yValueCount = count($yValues);
		$xValueCount = count($xValues);
		if (($yValueCount == 0) || ($yValueCount != $xValueCount)) {
			return self::$_errorCodes['na'];
		}
		else if ($yValueCount == 1) {
			return self::$_errorCodes['divisionbyzero'];
		}

		$bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
		return $bestFitLinear->getGoodnessOfFit();
	}

	static public function SLOPE($yValues, $xValues)
	{
		if (!self::_checkTrendArrays($yValues, $xValues)) {
			return self::$_errorCodes['value'];
		}

		$yValueCount = count($yValues);
		$xValueCount = count($xValues);
		if (($yValueCount == 0) || ($yValueCount != $xValueCount)) {
			return self::$_errorCodes['na'];
		}
		else if ($yValueCount == 1) {
			return self::$_errorCodes['divisionbyzero'];
		}

		$bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
		return $bestFitLinear->getSlope();
	}

	static public function STEYX($yValues, $xValues)
	{
		if (!self::_checkTrendArrays($yValues, $xValues)) {
			return self::$_errorCodes['value'];
		}

		$yValueCount = count($yValues);
		$xValueCount = count($xValues);
		if (($yValueCount == 0) || ($yValueCount != $xValueCount)) {
			return self::$_errorCodes['na'];
		}
		else if ($yValueCount == 1) {
			return self::$_errorCodes['divisionbyzero'];
		}

		$bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
		return $bestFitLinear->getStdevOfResiduals();
	}

	static public function COVAR($yValues, $xValues)
	{
		if (!self::_checkTrendArrays($yValues, $xValues)) {
			return self::$_errorCodes['value'];
		}

		$yValueCount = count($yValues);
		$xValueCount = count($xValues);
		if (($yValueCount == 0) || ($yValueCount != $xValueCount)) {
			return self::$_errorCodes['na'];
		}
		else if ($yValueCount == 1) {
			return self::$_errorCodes['divisionbyzero'];
		}

		$bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
		return $bestFitLinear->getCovariance();
	}

	static public function CORREL($yValues, $xValues = NULL)
	{
		if (is_null($xValues) || !is_array($yValues) || !is_array($xValues)) {
			return self::$_errorCodes['value'];
		}

		if (!self::_checkTrendArrays($yValues, $xValues)) {
			return self::$_errorCodes['value'];
		}

		$yValueCount = count($yValues);
		$xValueCount = count($xValues);
		if (($yValueCount == 0) || ($yValueCount != $xValueCount)) {
			return self::$_errorCodes['na'];
		}
		else if ($yValueCount == 1) {
			return self::$_errorCodes['divisionbyzero'];
		}

		$bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
		return $bestFitLinear->getCorrelation();
	}

	static public function LINEST($yValues, $xValues = NULL, $const = true, $stats = false)
	{
		$const = (is_null($const) ? true : (bool) self::flattenSingleValue($const));
		$stats = (is_null($stats) ? false : (bool) self::flattenSingleValue($stats));

		if (is_null($xValues)) {
			$xValues = range(1, count(self::flattenArray($yValues)));
		}

		if (!self::_checkTrendArrays($yValues, $xValues)) {
			return self::$_errorCodes['value'];
		}

		$yValueCount = count($yValues);
		$xValueCount = count($xValues);
		if (($yValueCount == 0) || ($yValueCount != $xValueCount)) {
			return self::$_errorCodes['na'];
		}
		else if ($yValueCount == 1) {
			return 0;
		}

		$bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues, $const);

		if ($stats) {
			return array(
	array($bestFitLinear->getSlope(), $bestFitLinear->getSlopeSE(), $bestFitLinear->getGoodnessOfFit(), $bestFitLinear->getF(), $bestFitLinear->getSSRegression()),
	array($bestFitLinear->getIntersect(), $bestFitLinear->getIntersectSE(), $bestFitLinear->getStdevOfResiduals(), $bestFitLinear->getDFResiduals(), $bestFitLinear->getSSResiduals())
	);
		}
		else {
			return array($bestFitLinear->getSlope(), $bestFitLinear->getIntersect());
		}
	}

	static public function LOGEST($yValues, $xValues = NULL, $const = true, $stats = false)
	{
		$const = (is_null($const) ? true : (bool) self::flattenSingleValue($const));
		$stats = (is_null($stats) ? false : (bool) self::flattenSingleValue($stats));

		if (is_null($xValues)) {
			$xValues = range(1, count(self::flattenArray($yValues)));
		}

		if (!self::_checkTrendArrays($yValues, $xValues)) {
			return self::$_errorCodes['value'];
		}

		$yValueCount = count($yValues);
		$xValueCount = count($xValues);

		foreach ($yValues as $value) {
			if ($value <= 0) {
				return self::$_errorCodes['num'];
			}
		}

		if (($yValueCount == 0) || ($yValueCount != $xValueCount)) {
			return self::$_errorCodes['na'];
		}
		else if ($yValueCount == 1) {
			return 1;
		}

		$bestFitExponential = trendClass::calculate(trendClass::TREND_EXPONENTIAL, $yValues, $xValues, $const);

		if ($stats) {
			return array(
	array($bestFitExponential->getSlope(), $bestFitExponential->getSlopeSE(), $bestFitExponential->getGoodnessOfFit(), $bestFitExponential->getF(), $bestFitExponential->getSSRegression()),
	array($bestFitExponential->getIntersect(), $bestFitExponential->getIntersectSE(), $bestFitExponential->getStdevOfResiduals(), $bestFitExponential->getDFResiduals(), $bestFitExponential->getSSResiduals())
	);
		}
		else {
			return array($bestFitExponential->getSlope(), $bestFitExponential->getIntersect());
		}
	}

	static public function FORECAST($xValue, $yValues, $xValues)
	{
		$xValue = self::flattenSingleValue($xValue);

		if (!is_numeric($xValue)) {
			return self::$_errorCodes['value'];
		}

		if (!self::_checkTrendArrays($yValues, $xValues)) {
			return self::$_errorCodes['value'];
		}

		$yValueCount = count($yValues);
		$xValueCount = count($xValues);
		if (($yValueCount == 0) || ($yValueCount != $xValueCount)) {
			return self::$_errorCodes['na'];
		}
		else if ($yValueCount == 1) {
			return self::$_errorCodes['divisionbyzero'];
		}

		$bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
		return $bestFitLinear->getValueOfYForX($xValue);
	}

	static public function TREND($yValues, $xValues = array(), $newValues = array(), $const = true)
	{
		$yValues = self::flattenArray($yValues);
		$xValues = self::flattenArray($xValues);
		$newValues = self::flattenArray($newValues);
		$const = (is_null($const) ? true : (bool) self::flattenSingleValue($const));
		$bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues, $const);

		if (count($newValues) == 0) {
			$newValues = $bestFitLinear->getXValues();
		}

		$returnArray = array();

		foreach ($newValues as $xValue) {
			$returnArray[0][] = $bestFitLinear->getValueOfYForX($xValue);
		}

		return $returnArray;
	}

	static public function GROWTH($yValues, $xValues = array(), $newValues = array(), $const = true)
	{
		$yValues = self::flattenArray($yValues);
		$xValues = self::flattenArray($xValues);
		$newValues = self::flattenArray($newValues);
		$const = (is_null($const) ? true : (bool) self::flattenSingleValue($const));
		$bestFitExponential = trendClass::calculate(trendClass::TREND_EXPONENTIAL, $yValues, $xValues, $const);

		if (count($newValues) == 0) {
			$newValues = $bestFitExponential->getXValues();
		}

		$returnArray = array();

		foreach ($newValues as $xValue) {
			$returnArray[0][] = $bestFitExponential->getValueOfYForX($xValue);
		}

		return $returnArray;
	}

	static private function _romanCut($num, $n)
	{
		return ($num - ($num % $n)) / $n;
	}

	static public function ROMAN($aValue, $style = 0)
	{
		$aValue = (int) self::flattenSingleValue($aValue);
		$style = (is_null($style) ? 0 : (int) self::flattenSingleValue($style));
		if (!is_numeric($aValue) || ($aValue < 0) || (4000 <= $aValue)) {
			return self::$_errorCodes['value'];
		}

		if ($aValue == 0) {
			return '';
		}

		$mill = array('', 'M', 'MM', 'MMM', 'MMMM', 'MMMMM');
		$cent = array('', 'C', 'CC', 'CCC', 'CD', 'D', 'DC', 'DCC', 'DCCC', 'CM');
		$tens = array('', 'X', 'XX', 'XXX', 'XL', 'L', 'LX', 'LXX', 'LXXX', 'XC');
		$ones = array('', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX');
		$roman = '';

		while (5999 < $aValue) {
			$roman .= 'M';
			$aValue -= 1000;
		}

		$m = self::_romanCut($aValue, 1000);
		$aValue %= 1000;
		$c = self::_romanCut($aValue, 100);
		$aValue %= 100;
		$t = self::_romanCut($aValue, 10);
		$aValue %= 10;
		return $roman . $mill[$m] . $cent[$c] . $tens[$t] . $ones[$aValue];
	}

	static public function SUBTOTAL()
	{
		$aArgs = self::flattenArray(func_get_args());
		$subtotal = array_shift($aArgs);
		if (is_numeric($subtotal) && !is_string($subtotal)) {
			switch ($subtotal) {
			case 1:
				return self::AVERAGE($aArgs);
				break;

			case 2:
				return self::COUNT($aArgs);
				break;

			case 3:
				return self::COUNTA($aArgs);
				break;

			case 4:
				return self::MAX($aArgs);
				break;

			case 5:
				return self::MIN($aArgs);
				break;

			case 6:
				return self::PRODUCT($aArgs);
				break;

			case 7:
				return self::STDEV($aArgs);
				break;

			case 8:
				return self::STDEVP($aArgs);
				break;

			case 9:
				return self::SUM($aArgs);
				break;

			case 10:
				return self::VARFunc($aArgs);
				break;

			case 11:
				return self::VARP($aArgs);
				break;
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function SQRTPI($number)
	{
		$number = self::flattenSingleValue($number);

		if (is_numeric($number)) {
			if ($number < 0) {
				return self::$_errorCodes['num'];
			}

			return sqrt($number * M_PI);
		}

		return self::$_errorCodes['value'];
	}

	static public function FACT($factVal)
	{
		$factVal = self::flattenSingleValue($factVal);

		if (is_numeric($factVal)) {
			if ($factVal < 0) {
				return self::$_errorCodes['num'];
			}

			$factLoop = floor($factVal);

			if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
				if ($factLoop < $factVal) {
					return self::$_errorCodes['num'];
				}
			}

			$factorial = 1;

			while (1 < $factLoop) {
				$factorial *= $factLoop--;
			}

			return $factorial;
		}

		return self::$_errorCodes['value'];
	}

	static public function FACTDOUBLE($factVal)
	{
		$factLoop = floor(self::flattenSingleValue($factVal));

		if (is_numeric($factLoop)) {
			if ($factVal < 0) {
				return self::$_errorCodes['num'];
			}

			$factorial = 1;

			while (1 < $factLoop) {
				$factorial *= $factLoop--;
				--$factLoop;
			}

			return $factorial;
		}

		return self::$_errorCodes['value'];
	}

	static public function MULTINOMIAL()
	{
		$aArgs = self::flattenArray(func_get_args());
		$summer = 0;
		$divisor = 1;

		foreach ($aArgs as $arg) {
			if (is_numeric($arg)) {
				if ($arg < 1) {
					return self::$_errorCodes['num'];
				}

				$summer += floor($arg);
				$divisor *= self::FACT($arg);
			}
			else {
				return self::$_errorCodes['value'];
			}
		}

		if (0 < $summer) {
			$summer = self::FACT($summer);
			return $summer / $divisor;
		}

		return 0;
	}

	static public function CEILING($number, $significance = NULL)
	{
		$number = self::flattenSingleValue($number);
		$significance = self::flattenSingleValue($significance);
		if (is_null($significance) && (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC)) {
			$significance = $number / abs($number);
		}

		if (is_numeric($number) && is_numeric($significance)) {
			if (self::SIGN($number) == self::SIGN($significance)) {
				if ($significance == 0) {
					return 0;
				}

				return ceil($number / $significance) * $significance;
			}
			else {
				return self::$_errorCodes['num'];
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function EVEN($number)
	{
		$number = self::flattenSingleValue($number);

		if (is_numeric($number)) {
			$significance = 2 * self::SIGN($number);
			return self::CEILING($number, $significance);
		}

		return self::$_errorCodes['value'];
	}

	static public function ODD($number)
	{
		$number = self::flattenSingleValue($number);

		if (is_numeric($number)) {
			$significance = self::SIGN($number);

			if ($significance == 0) {
				return 1;
			}

			$result = self::CEILING($number, $significance);

			if (self::IS_EVEN($result)) {
				$result += $significance;
			}

			return $result;
		}

		return self::$_errorCodes['value'];
	}

	static public function INTVALUE($number)
	{
		$number = self::flattenSingleValue($number);

		if (is_numeric($number)) {
			return (int) floor($number);
		}

		return self::$_errorCodes['value'];
	}

	static public function ROUNDUP($number, $digits)
	{
		$number = self::flattenSingleValue($number);
		$digits = self::flattenSingleValue($digits);
		if (is_numeric($number) && is_numeric($digits)) {
			$significance = pow(10, $digits);

			if ($number < 0) {
				return floor($number * $significance) / $significance;
			}
			else {
				return ceil($number * $significance) / $significance;
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function ROUNDDOWN($number, $digits)
	{
		$number = self::flattenSingleValue($number);
		$digits = self::flattenSingleValue($digits);
		if (is_numeric($number) && is_numeric($digits)) {
			$significance = pow(10, $digits);

			if ($number < 0) {
				return ceil($number * $significance) / $significance;
			}
			else {
				return floor($number * $significance) / $significance;
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function MROUND($number, $multiple)
	{
		$number = self::flattenSingleValue($number);
		$multiple = self::flattenSingleValue($multiple);
		if (is_numeric($number) && is_numeric($multiple)) {
			if ($multiple == 0) {
				return 0;
			}

			if (self::SIGN($number) == self::SIGN($multiple)) {
				$multiplier = 1 / $multiple;
				return round($number * $multiplier) / $multiplier;
			}

			return self::$_errorCodes['num'];
		}

		return self::$_errorCodes['value'];
	}

	static public function SIGN($number)
	{
		$number = self::flattenSingleValue($number);

		if (is_numeric($number)) {
			if ($number == 0) {
				return 0;
			}

			return $number / abs($number);
		}

		return self::$_errorCodes['value'];
	}

	static public function FLOOR($number, $significance = NULL)
	{
		$number = self::flattenSingleValue($number);
		$significance = self::flattenSingleValue($significance);
		if (is_null($significance) && (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC)) {
			$significance = $number / abs($number);
		}

		if (is_numeric($number) && is_numeric($significance)) {
			if ((double) $significance == 0) {
				return self::$_errorCodes['divisionbyzero'];
			}

			if (self::SIGN($number) == self::SIGN($significance)) {
				return floor($number / $significance) * $significance;
			}
			else {
				return self::$_errorCodes['num'];
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function PERMUT($numObjs, $numInSet)
	{
		$numObjs = self::flattenSingleValue($numObjs);
		$numInSet = self::flattenSingleValue($numInSet);
		if (is_numeric($numObjs) && is_numeric($numInSet)) {
			$numInSet = floor($numInSet);

			if ($numObjs < $numInSet) {
				return self::$_errorCodes['num'];
			}

			return round(self::FACT($numObjs) / self::FACT($numObjs - $numInSet));
		}

		return self::$_errorCodes['value'];
	}

	static public function COMBIN($numObjs, $numInSet)
	{
		$numObjs = self::flattenSingleValue($numObjs);
		$numInSet = self::flattenSingleValue($numInSet);
		if (is_numeric($numObjs) && is_numeric($numInSet)) {
			if ($numObjs < $numInSet) {
				return self::$_errorCodes['num'];
			}
			else if ($numInSet < 0) {
				return self::$_errorCodes['num'];
			}

			return round(self::FACT($numObjs) / self::FACT($numObjs - $numInSet)) / self::FACT($numInSet);
		}

		return self::$_errorCodes['value'];
	}

	static public function SERIESSUM()
	{
		$returnValue = 0;
		$aArgs = self::flattenArray(func_get_args());
		$x = array_shift($aArgs);
		$n = array_shift($aArgs);
		$m = array_shift($aArgs);
		if (is_numeric($x) && is_numeric($n) && is_numeric($m)) {
			$i = 0;

			foreach ($aArgs as $arg) {
				if (is_numeric($arg) && !is_string($arg)) {
					$returnValue += $arg * pow($x, $n + ($m * $i++));
				}
				else {
					return self::$_errorCodes['value'];
				}
			}

			return $returnValue;
		}

		return self::$_errorCodes['value'];
	}

	static public function STANDARDIZE($value, $mean, $stdDev)
	{
		$value = self::flattenSingleValue($value);
		$mean = self::flattenSingleValue($mean);
		$stdDev = self::flattenSingleValue($stdDev);
		if (is_numeric($value) && is_numeric($mean) && is_numeric($stdDev)) {
			if ($stdDev <= 0) {
				return self::$_errorCodes['num'];
			}

			return ($value - $mean) / $stdDev;
		}

		return self::$_errorCodes['value'];
	}

	static private function _factors($value)
	{
		$startVal = floor(sqrt($value));
		$factorArray = array();

		for ($i = $startVal; 1 < $i; --$i) {
			if (($value % $i) == 0) {
				$factorArray = array_merge($factorArray, self::_factors($value / $i));
				$factorArray = array_merge($factorArray, self::_factors($i));

				if ($i <= sqrt($value)) {
					break;
				}
			}
		}

		if (0 < count($factorArray)) {
			rsort($factorArray);
			return $factorArray;
		}
		else {
			return array((int) $value);
		}
	}

	static public function LCM()
	{
		$aArgs = self::flattenArray(func_get_args());
		$returnValue = 1;
		$allPoweredFactors = array();

		foreach ($aArgs as $value) {
			if (!is_numeric($value)) {
				return self::$_errorCodes['value'];
			}

			if ($value == 0) {
				return 0;
			}
			else if ($value < 0) {
				return self::$_errorCodes['num'];
			}

			$myFactors = self::_factors(floor($value));
			$myCountedFactors = array_count_values($myFactors);
			$myPoweredFactors = array();

			foreach ($myCountedFactors as $myCountedFactor => $myCountedPower) {
				$myPoweredFactors[$myCountedFactor] = pow($myCountedFactor, $myCountedPower);
			}

			foreach ($myPoweredFactors as $myPoweredValue => $myPoweredFactor) {
				if (array_key_exists($myPoweredValue, $allPoweredFactors)) {
					if ($allPoweredFactors[$myPoweredValue] < $myPoweredFactor) {
						$allPoweredFactors[$myPoweredValue] = $myPoweredFactor;
					}
				}
				else {
					$allPoweredFactors[$myPoweredValue] = $myPoweredFactor;
				}
			}
		}

		foreach ($allPoweredFactors as $allPoweredFactor) {
			$returnValue *= (int) $allPoweredFactor;
		}

		return $returnValue;
	}

	static public function GCD()
	{
		$aArgs = self::flattenArray(func_get_args());
		$returnValue = 1;
		$allPoweredFactors = array();

		foreach ($aArgs as $value) {
			if ($value == 0) {
				break;
			}

			$myFactors = self::_factors($value);
			$myCountedFactors = array_count_values($myFactors);
			$allValuesFactors[] = $myCountedFactors;
		}

		$allValuesCount = count($allValuesFactors);
		$mergedArray = $allValuesFactors[0];

		for ($i = 1; $i < $allValuesCount; ++$i) {
			$mergedArray = array_intersect_key($mergedArray, $allValuesFactors[$i]);
		}

		$mergedArrayValues = count($mergedArray);

		if ($mergedArrayValues == 0) {
			return $returnValue;
		}
		else if (1 < $mergedArrayValues) {
			foreach ($mergedArray as $mergedKey => $mergedValue) {
				foreach ($allValuesFactors as $highestPowerTest) {
					foreach ($highestPowerTest as $testKey => $testValue) {
						if (($testKey == $mergedKey) && ($testValue < $mergedValue)) {
							$mergedArray[$mergedKey] = $testValue;
							$mergedValue = $testValue;
						}
					}
				}
			}

			$returnValue = 1;

			foreach ($mergedArray as $key => $value) {
				$returnValue *= pow($key, $value);
			}

			return $returnValue;
		}
		else {
			$keys = array_keys($mergedArray);
			$key = $keys[0];
			$value = $mergedArray[$key];

			foreach ($allValuesFactors as $testValue) {
				foreach ($testValue as $mergedKey => $mergedValue) {
					if (($mergedKey == $key) && ($mergedValue < $value)) {
						$value = $mergedValue;
					}
				}
			}

			return pow($key, $value);
		}
	}

	static public function BINOMDIST($value, $trials, $probability, $cumulative)
	{
		$value = floor(self::flattenSingleValue($value));
		$trials = floor(self::flattenSingleValue($trials));
		$probability = self::flattenSingleValue($probability);
		if (is_numeric($value) && is_numeric($trials) && is_numeric($probability)) {
			if (($value < 0) || ($trials < $value)) {
				return self::$_errorCodes['num'];
			}

			if (($probability < 0) || (1 < $probability)) {
				return self::$_errorCodes['num'];
			}

			if (is_numeric($cumulative) || is_bool($cumulative)) {
				if ($cumulative) {
					$summer = 0;

					for ($i = 0; $i <= $value; ++$i) {
						$summer += self::COMBIN($trials, $i) * pow($probability, $i) * pow(1 - $probability, $trials - $i);
					}

					return $summer;
				}
				else {
					return self::COMBIN($trials, $value) * pow($probability, $value) * pow(1 - $probability, $trials - $value);
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function NEGBINOMDIST($failures, $successes, $probability)
	{
		$failures = floor(self::flattenSingleValue($failures));
		$successes = floor(self::flattenSingleValue($successes));
		$probability = self::flattenSingleValue($probability);
		if (is_numeric($failures) && is_numeric($successes) && is_numeric($probability)) {
			if (($failures < 0) || ($successes < 1)) {
				return self::$_errorCodes['num'];
			}

			if (($probability < 0) || (1 < $probability)) {
				return self::$_errorCodes['num'];
			}

			if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
				if ((($failures + $successes) - 1) <= 0) {
					return self::$_errorCodes['num'];
				}
			}

			return self::COMBIN(($failures + $successes) - 1, $successes - 1) * pow($probability, $successes) * pow(1 - $probability, $failures);
		}

		return self::$_errorCodes['value'];
	}

	static public function CRITBINOM($trials, $probability, $alpha)
	{
		$trials = floor(self::flattenSingleValue($trials));
		$probability = self::flattenSingleValue($probability);
		$alpha = self::flattenSingleValue($alpha);
		if (is_numeric($trials) && is_numeric($probability) && is_numeric($alpha)) {
			if ($trials < 0) {
				return self::$_errorCodes['num'];
			}

			if (($probability < 0) || (1 < $probability)) {
				return self::$_errorCodes['num'];
			}

			if (($alpha < 0) || (1 < $alpha)) {
				return self::$_errorCodes['num'];
			}

			if ($alpha <= 0.5) {
				$t = sqrt(log(1 / ($alpha * $alpha)));
				$trialsApprox = 0 - ($t + ((2.515517 + (0.80285300000000004 * $t) + (0.010328 * $t * $t)) / (1 + (1.432788 * $t) + (0.18926899999999999 * $t * $t) + (0.0013079999999999999 * $t * $t * $t))));
			}
			else {
				$t = sqrt(log(1 / pow(1 - $alpha, 2)));
				$trialsApprox = $t - ((2.515517 + (0.80285300000000004 * $t) + (0.010328 * $t * $t)) / (1 + (1.432788 * $t) + (0.18926899999999999 * $t * $t) + (0.0013079999999999999 * $t * $t * $t)));
			}

			$Guess = floor(($trials * $probability) + ($trialsApprox * sqrt($trials * $probability * (1 - $probability))));

			if ($Guess < 0) {
				$Guess = 0;
			}
			else if ($trials < $Guess) {
				$Guess = $trials;
			}

			$TotalUnscaledProbability = $UnscaledPGuess = $UnscaledCumPGuess = 0;
			$EssentiallyZero = 9.9999999999999994E-12;
			$m = floor($trials * $probability);
			++$TotalUnscaledProbability;

			if ($m == $Guess) {
				++$UnscaledPGuess;
			}

			if ($m <= $Guess) {
				++$UnscaledCumPGuess;
			}

			$PreviousValue = 1;
			$Done = false;
			$k = $m + 1;

			while ($k <= $trials) {
				$CurrentValue = ($PreviousValue * (($trials - $k) + 1) * $probability) / ($k * (1 - $probability));
				$TotalUnscaledProbability += $CurrentValue;

				if ($k == $Guess) {
					$UnscaledPGuess += $CurrentValue;
				}

				if ($k <= $Guess) {
					$UnscaledCumPGuess += $CurrentValue;
				}

				if ($CurrentValue <= $EssentiallyZero) {
					$Done = true;
				}

				$PreviousValue = $CurrentValue;
				++$k;
			}

			$PreviousValue = 1;
			$Done = false;
			$k = $m - 1;

			while (0 <= $k) {
				$CurrentValue = ($PreviousValue * $k) + ((1 * (1 - $probability)) / (($trials - $k) * $probability));
				$TotalUnscaledProbability += $CurrentValue;

				if ($k == $Guess) {
					$UnscaledPGuess += $CurrentValue;
				}

				if ($k <= $Guess) {
					$UnscaledCumPGuess += $CurrentValue;
				}

				if ($CurrentValue <= $EssentiallyZero) {
					$Done = true;
				}

				$PreviousValue = $CurrentValue;
				--$k;
			}

			$PGuess = $UnscaledPGuess / $TotalUnscaledProbability;
			$CumPGuess = $UnscaledCumPGuess / $TotalUnscaledProbability;
			$CumPGuessMinus1 = $CumPGuess - 1;

			while (true) {
				if (($CumPGuessMinus1 < $alpha) && ($alpha <= $CumPGuess)) {
					return $Guess;
				}
				else {
					if (($CumPGuessMinus1 < $alpha) && ($CumPGuess < $alpha)) {
						$PGuessPlus1 = ($PGuess * ($trials - $Guess) * $probability) / $Guess / (1 - $probability);
						$CumPGuessMinus1 = $CumPGuess;
						$CumPGuess = $CumPGuess + $PGuessPlus1;
						$PGuess = $PGuessPlus1;
						++$Guess;
					}
					else {
						if (($alpha <= $CumPGuessMinus1) && ($alpha <= $CumPGuess)) {
							$PGuessMinus1 = ($PGuess * $Guess * (1 - $probability)) / (($trials - $Guess) + 1) / $probability;
							$CumPGuess = $CumPGuessMinus1;
							$CumPGuessMinus1 = $CumPGuessMinus1 - $PGuess;
							$PGuess = $PGuessMinus1;
							--$Guess;
						}
					}
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function CHIDIST($value, $degrees)
	{
		$value = self::flattenSingleValue($value);
		$degrees = floor(self::flattenSingleValue($degrees));
		if (is_numeric($value) && is_numeric($degrees)) {
			if ($degrees < 1) {
				return self::$_errorCodes['num'];
			}

			if ($value < 0) {
				if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
					return 1;
				}

				return self::$_errorCodes['num'];
			}

			return 1 - (self::_incompleteGamma($degrees / 2, $value / 2) / self::_gamma($degrees / 2));
		}

		return self::$_errorCodes['value'];
	}

	static public function CHIINV($probability, $degrees)
	{
		$probability = self::flattenSingleValue($probability);
		$degrees = floor(self::flattenSingleValue($degrees));
		if (is_numeric($probability) && is_numeric($degrees)) {
			$xLo = 100;
			$xHi = 0;
			$x = $xNew = 1;
			$dx = 1;
			$i = 0;

			while ($i++ < MAX_ITERATIONS) {
				$result = self::CHIDIST($x, $degrees);
				$error = $result - $probability;

				if ($error == 0) {
					$dx = 0;
				}
				else if ($error < 0) {
					$xLo = $x;
				}
				else {
					$xHi = $x;
				}

				if ($result != 0) {
					$dx = $error / $result;
					$xNew = $x - $dx;
				}

				if (($xNew < $xLo) || ($xHi < $xNew) || ($result == 0)) {
					$xNew = ($xLo + $xHi) / 2;
					$dx = $xNew - $x;
				}

				$x = $xNew;
			}

			if ($i == MAX_ITERATIONS) {
				return self::$_errorCodes['na'];
			}

			return round($x, 12);
		}

		return self::$_errorCodes['value'];
	}

	static public function EXPONDIST($value, $lambda, $cumulative)
	{
		$value = self::flattenSingleValue($value);
		$lambda = self::flattenSingleValue($lambda);
		$cumulative = self::flattenSingleValue($cumulative);
		if (is_numeric($value) && is_numeric($lambda)) {
			if (($value < 0) || ($lambda < 0)) {
				return self::$_errorCodes['num'];
			}

			if (is_numeric($cumulative) || is_bool($cumulative)) {
				if ($cumulative) {
					return 1 - exp(0 - ($value * $lambda));
				}
				else {
					return $lambda * exp(0 - ($value * $lambda));
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function FISHER($value)
	{
		$value = self::flattenSingleValue($value);

		if (is_numeric($value)) {
			if (($value <= -1) || (1 <= $value)) {
				return self::$_errorCodes['num'];
			}

			return 0.5 * log((1 + $value) / (1 - $value));
		}

		return self::$_errorCodes['value'];
	}

	static public function FISHERINV($value)
	{
		$value = self::flattenSingleValue($value);

		if (is_numeric($value)) {
			return (exp(2 * $value) - 1) / (exp(2 * $value) + 1);
		}

		return self::$_errorCodes['value'];
	}

	static private function _logBeta($p, $q)
	{
		if (($p != self::$_logBetaCache_p) || ($q != self::$_logBetaCache_q)) {
			self::$_logBetaCache_p = $p;
			self::$_logBetaCache_q = $q;
			if (($p <= 0) || ($q <= 0) || (LOG_GAMMA_X_MAX_VALUE < ($p + $q))) {
				self::$_logBetaCache_result = 0;
			}
			else {
				self::$_logBetaCache_result = (self::_logGamma($p) + self::_logGamma($q)) - self::_logGamma($p + $q);
			}
		}

		return self::$_logBetaCache_result;
	}

	static private function _betaFraction($x, $p, $q)
	{
		$c = 1;
		$sum_pq = $p + $q;
		$p_plus = $p + 1;
		$p_minus = $p - 1;
		$h = 1 - (($sum_pq * $x) / $p_plus);

		if (abs($h) < XMININ) {
			$h = XMININ;
		}

		$h = 1 / $h;
		$frac = $h;
		$m = 1;
		$delta = 0;

		while (PRECISION < abs($delta - 1)) {
			$m2 = 2 * $m;
			$d = ($m * ($q - $m) * $x) / (($p_minus + $m2) * ($p + $m2));
			$h = 1 + ($d * $h);

			if (abs($h) < XMININ) {
				$h = XMININ;
			}

			$h = 1 / $h;
			$c = 1 + ($d / $c);

			if (abs($c) < XMININ) {
				$c = XMININ;
			}

			$frac *= $h * $c;
			$d = ((0 - ($p + $m)) * ($sum_pq + $m) * $x) / (($p + $m2) * ($p_plus + $m2));
			$h = 1 + ($d * $h);

			if (abs($h) < XMININ) {
				$h = XMININ;
			}

			$h = 1 / $h;
			$c = 1 + ($d / $c);

			if (abs($c) < XMININ) {
				$c = XMININ;
			}

			$delta = $h * $c;
			$frac *= $delta;
			++$m;
		}

		return $frac;
	}

	static private function _logGamma($x)
	{
		static $lg_d1 = -0.57721566490153287;
		static $lg_d2 = 0.42278433509846713;
		static $lg_d4 = 1.791759469228055;
		static $lg_p1 = array(4.9452353592967269, 201.8112620856775, 2290.8383738313464, 11319.672059033808, 28557.246356716354, 38484.962284437934, 26377.487876241954, 7225.8139797002877);
		static $lg_p2 = array(4.974607845568932, 542.4138599891071, 15506.938649783649, 184793.29044456323, 1088204.7694688288, 3338152.9679870298, 5106661.6789273527, 3074109.0548505397);
		static $lg_p4 = array(14745.021660599399, 2426813.3694867045, 121475557.40450932, 2663432449.6309772, 29403789566.345539, 170266573776.53989, 492612579337.7431, 560625185622.39514);
		static $lg_q1 = array(67.482125503037778, 1113.3323938571993, 7738.7570569353984, 27639.870744033407, 54993.102062261576, 61611.221800660023, 36351.275915019403, 8785.5363024310136);
		static $lg_q2 = array(183.03283993705926, 7765.0493214450062, 133190.38279660742, 1136705.8213219696, 5267964.1174379466, 13467014.543111017, 17827365.303532742, 9533095.5918443538);
		static $lg_q4 = array(2690.5301758708993, 639388.56543000927, 41355999.302413881, 1120872109.616148, 14886137286.788137, 101680358627.24382, 341747634550.73773, 446315818741.97131);
		static $lg_c = array(-0.0019104440777279999, 0.00084171387781295005, -0.00059523799130430121, 0.0007936507935003503, -0.0027777777777776816, 0.083333333333333329, 0.0057083835261000004);
		static $lg_frtbig = 2.2499999999999999E+76;
		static $pnt68 = 0.6796875;

		if ($x == self::$_logGammaCache_x) {
			return self::$_logGammaCache_result;
		}

		$y = $x;
		if ((0 < $y) && ($y <= LOG_GAMMA_X_MAX_VALUE)) {
			if ($y <= EPS) {
				$res = 0 - log(y);
			}
			else if ($y <= 1.5) {
				if ($y < $pnt68) {
					$corr = 0 - log($y);
					$xm1 = $y;
				}
				else {
					$corr = 0;
					$xm1 = $y - 1;
				}

				if (($y <= 0.5) || ($pnt68 <= $y)) {
					$xden = 1;
					$xnum = 0;

					for ($i = 0; $i < 8; ++$i) {
						$xnum = ($xnum * $xm1) + $lg_p1[$i];
						$xden = ($xden * $xm1) + $lg_q1[$i];
					}

					$res = $corr + ($xm1 * ($lg_d1 + ($xm1 * ($xnum / $xden))));
				}
				else {
					$xm2 = $y - 1;
					$xden = 1;
					$xnum = 0;

					for ($i = 0; $i < 8; ++$i) {
						$xnum = ($xnum * $xm2) + $lg_p2[$i];
						$xden = ($xden * $xm2) + $lg_q2[$i];
					}

					$res = $corr + ($xm2 * ($lg_d2 + ($xm2 * ($xnum / $xden))));
				}
			}
			else if ($y <= 4) {
				$xm2 = $y - 2;
				$xden = 1;
				$xnum = 0;

				for ($i = 0; $i < 8; ++$i) {
					$xnum = ($xnum * $xm2) + $lg_p2[$i];
					$xden = ($xden * $xm2) + $lg_q2[$i];
				}

				$res = $xm2 * ($lg_d2 + ($xm2 * ($xnum / $xden)));
			}
			else if ($y <= 12) {
				$xm4 = $y - 4;
				$xden = -1;
				$xnum = 0;

				for ($i = 0; $i < 8; ++$i) {
					$xnum = ($xnum * $xm4) + $lg_p4[$i];
					$xden = ($xden * $xm4) + $lg_q4[$i];
				}

				$res = $lg_d4 + ($xm4 * ($xnum / $xden));
			}
			else {
				$res = 0;

				if ($y <= $lg_frtbig) {
					$res = $lg_c[6];
					$ysq = $y * $y;

					for ($i = 0; $i < 6; ++$i) {
						$res = ($res / $ysq) + $lg_c[$i];
					}
				}

				$res /= $y;
				$corr = log($y);
				$res = ($res + log(SQRT2PI)) - (0.5 * $corr);
				$res += $y * ($corr - 1);
			}
		}
		else {
			$res = MAX_VALUE;
		}

		self::$_logGammaCache_x = $x;
		self::$_logGammaCache_result = $res;
		return $res;
	}

	static private function _beta($p, $q)
	{
		if (($p <= 0) || ($q <= 0) || (LOG_GAMMA_X_MAX_VALUE < ($p + $q))) {
			return 0;
		}
		else {
			return exp(self::_logBeta($p, $q));
		}
	}

	static private function _incompleteBeta($x, $p, $q)
	{
		if ($x <= 0) {
			return 0;
		}
		else if (1 <= $x) {
			return 1;
		}
		else {
			if (($p <= 0) || ($q <= 0) || (LOG_GAMMA_X_MAX_VALUE < ($p + $q))) {
				return 0;
			}
		}

		$beta_gam = exp((0 - self::_logBeta($p, $q)) + ($p * log($x)) + ($q * log(1 - $x)));

		if ($x < (($p + 1) / ($p + $q + 2))) {
			return ($beta_gam * self::_betaFraction($x, $p, $q)) / $p;
		}
		else {
			return 1 - (($beta_gam * self::_betaFraction(1 - $x, $q, $p)) / $q);
		}
	}

	static public function BETADIST($value, $alpha, $beta, $rMin = 0, $rMax = 1)
	{
		$value = self::flattenSingleValue($value);
		$alpha = self::flattenSingleValue($alpha);
		$beta = self::flattenSingleValue($beta);
		$rMin = self::flattenSingleValue($rMin);
		$rMax = self::flattenSingleValue($rMax);
		if (is_numeric($value) && is_numeric($alpha) && is_numeric($beta) && is_numeric($rMin) && is_numeric($rMax)) {
			if (($value < $rMin) || ($rMax < $value) || ($alpha <= 0) || ($beta <= 0) || ($rMin == $rMax)) {
				return self::$_errorCodes['num'];
			}

			if ($rMax < $rMin) {
				$tmp = $rMin;
				$rMin = $rMax;
				$rMax = $tmp;
			}

			$value -= $rMin;
			$value /= $rMax - $rMin;
			return self::_incompleteBeta($value, $alpha, $beta);
		}

		return self::$_errorCodes['value'];
	}

	static public function BETAINV($probability, $alpha, $beta, $rMin = 0, $rMax = 1)
	{
		$probability = self::flattenSingleValue($probability);
		$alpha = self::flattenSingleValue($alpha);
		$beta = self::flattenSingleValue($beta);
		$rMin = self::flattenSingleValue($rMin);
		$rMax = self::flattenSingleValue($rMax);
		if (is_numeric($probability) && is_numeric($alpha) && is_numeric($beta) && is_numeric($rMin) && is_numeric($rMax)) {
			if (($alpha <= 0) || ($beta <= 0) || ($rMin == $rMax) || ($probability <= 0) || (1 < $probability)) {
				return self::$_errorCodes['num'];
			}

			if ($rMax < $rMin) {
				$tmp = $rMin;
				$rMin = $rMax;
				$rMax = $tmp;
			}

			$a = 0;
			$b = 2;
			$i = 0;

			while ($i++ < MAX_ITERATIONS) {
				$guess = ($a + $b) / 2;
				$result = self::BETADIST($guess, $alpha, $beta);
				if (($result == $probability) || ($result == 0)) {
					$b = $a;
				}
				else if ($probability < $result) {
					$b = $guess;
				}
				else {
					$a = $guess;
				}
			}

			if ($i == MAX_ITERATIONS) {
				return self::$_errorCodes['na'];
			}

			return round($rMin + ($guess * ($rMax - $rMin)), 12);
		}

		return self::$_errorCodes['value'];
	}

	static private function _incompleteGamma($a, $x)
	{
		static $max = 32;
		$summer = 0;

		for ($n = 0; $n <= $max; ++$n) {
			$divisor = $a;

			for ($i = 1; $i <= $n; ++$i) {
				$divisor *= $a + $i;
			}

			$summer += pow($x, $n) / $divisor;
		}

		return pow($x, $a) * exp(0 - $x) * $summer;
	}

	static private function _gamma($data)
	{
		if ($data == 0) {
			return 0;
		}

		static $p0 = 1.0000000001900149;
		static $p = array(1 => 76.180091729471457, 2 => -86.505320329416776, 3 => 24.014098240830911, 4 => -1.231739572450155, 5 => 0.001208650973866179, 6 => -5.3952393849530003E-6);
		$y = $x = $data;
		$tmp = $x + 5.5;
		$tmp -= ($x + 0.5) * log($tmp);
		$summer = $p0;

		for ($j = 1; $j <= 6; ++$j) {
			$summer += $p[$j] / ++$y;
		}

		return exp((0 - $tmp) + log((SQRT2PI * $summer) / $x));
	}

	static public function GAMMADIST($value, $a, $b, $cumulative)
	{
		$value = self::flattenSingleValue($value);
		$a = self::flattenSingleValue($a);
		$b = self::flattenSingleValue($b);
		if (is_numeric($value) && is_numeric($a) && is_numeric($b)) {
			if (($value < 0) || ($a <= 0) || ($b <= 0)) {
				return self::$_errorCodes['num'];
			}

			if (is_numeric($cumulative) || is_bool($cumulative)) {
				if ($cumulative) {
					return self::_incompleteGamma($a, $value / $b) / self::_gamma($a);
				}
				else {
					return (1 / (pow($b, $a) * self::_gamma($a))) * pow($value, $a - 1) * exp(0 - ($value / $b));
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function GAMMAINV($probability, $alpha, $beta)
	{
		$probability = self::flattenSingleValue($probability);
		$alpha = self::flattenSingleValue($alpha);
		$beta = self::flattenSingleValue($beta);
		if (is_numeric($probability) && is_numeric($alpha) && is_numeric($beta)) {
			if (($alpha <= 0) || ($beta <= 0) || ($probability < 0) || (1 < $probability)) {
				return self::$_errorCodes['num'];
			}

			$xLo = 0;
			$xHi = $alpha * $beta * 5;
			$x = $xNew = 1;
			$error = $pdf = 0;
			$dx = 1024;
			$i = 0;

			while ($i++ < MAX_ITERATIONS) {
				$error = self::GAMMADIST($x, $alpha, $beta, true) - $probability;

				if ($error < 0) {
					$xLo = $x;
				}
				else {
					$xHi = $x;
				}

				$pdf = self::GAMMADIST($x, $alpha, $beta, false);

				if ($pdf != 0) {
					$dx = $error / $pdf;
					$xNew = $x - $dx;
				}

				if (($xNew < $xLo) || ($xHi < $xNew) || ($pdf == 0)) {
					$xNew = ($xLo + $xHi) / 2;
					$dx = $xNew - $x;
				}

				$x = $xNew;
			}

			if ($i == MAX_ITERATIONS) {
				return self::$_errorCodes['na'];
			}

			return $x;
		}

		return self::$_errorCodes['value'];
	}

	static public function GAMMALN($value)
	{
		$value = self::flattenSingleValue($value);

		if (is_numeric($value)) {
			if ($value <= 0) {
				return self::$_errorCodes['num'];
			}

			return log(self::_gamma($value));
		}

		return self::$_errorCodes['value'];
	}

	static public function NORMDIST($value, $mean, $stdDev, $cumulative)
	{
		$value = self::flattenSingleValue($value);
		$mean = self::flattenSingleValue($mean);
		$stdDev = self::flattenSingleValue($stdDev);
		if (is_numeric($value) && is_numeric($mean) && is_numeric($stdDev)) {
			if ($stdDev < 0) {
				return self::$_errorCodes['num'];
			}

			if (is_numeric($cumulative) || is_bool($cumulative)) {
				if ($cumulative) {
					return 0.5 * (1 + self::_erfVal(($value - $mean) / ($stdDev * sqrt(2))));
				}
				else {
					return (1 / (SQRT2PI * $stdDev)) * exp(0 - (pow($value - $mean, 2) / (2 * $stdDev * $stdDev)));
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function NORMSDIST($value)
	{
		$value = self::flattenSingleValue($value);
		return self::NORMDIST($value, 0, 1, true);
	}

	static public function LOGNORMDIST($value, $mean, $stdDev)
	{
		$value = self::flattenSingleValue($value);
		$mean = self::flattenSingleValue($mean);
		$stdDev = self::flattenSingleValue($stdDev);
		if (is_numeric($value) && is_numeric($mean) && is_numeric($stdDev)) {
			if (($value <= 0) || ($stdDev <= 0)) {
				return self::$_errorCodes['num'];
			}

			return self::NORMSDIST((log($value) - $mean) / $stdDev);
		}

		return self::$_errorCodes['value'];
	}

	static private function _inverse_ncdf($p)
	{
		static $a = array(1 => -39.696830286653757, 2 => 220.9460984245205, 3 => -275.92851044696869, 4 => 138.357751867269, 5 => -30.66479806614716, 6 => 2.5066282774592392);
		static $b = array(1 => -54.476098798224058, 2 => 161.58583685804089, 3 => -155.69897985988661, 4 => 66.80131188771972, 5 => -13.280681552885721);
		static $c = array(1 => -0.0077848940024302926, 2 => -0.32239645804113648, 3 => -2.4007582771618381, 4 => -2.5497325393437338, 5 => 4.3746641414649678, 6 => 2.9381639826987831);
		static $d = array(1 => 0.0077846957090414622, 2 => 0.32246712907003983, 3 => 2.445134137142996, 4 => 3.7544086619074162);
		$p_low = 0.024250000000000001;
		$p_high = 1 - $p_low;
		if ((0 < $p) && ($p < $p_low)) {
			$q = sqrt(-2 * log($p));
			return (((((((((($c[1] * $q) + $c[2]) * $q) + $c[3]) * $q) + $c[4]) * $q) + $c[5]) * $q) + $c[6]) / (((((((($d[1] * $q) + $d[2]) * $q) + $d[3]) * $q) + $d[4]) * $q) + 1);
		}
		else {
			if (($p_low <= $p) && ($p <= $p_high)) {
				$q = $p - 0.5;
				$r = $q * $q;
				return ((((((((((($a[1] * $r) + $a[2]) * $r) + $a[3]) * $r) + $a[4]) * $r) + $a[5]) * $r) + $a[6]) * $q) / (((((((((($b[1] * $r) + $b[2]) * $r) + $b[3]) * $r) + $b[4]) * $r) + $b[5]) * $r) + 1);
			}
			else {
				if (($p_high < $p) && ($p < 1)) {
					$q = sqrt(-2 * log(1 - $p));
					return (0 - (((((((((($c[1] * $q) + $c[2]) * $q) + $c[3]) * $q) + $c[4]) * $q) + $c[5]) * $q) + $c[6])) / (((((((($d[1] * $q) + $d[2]) * $q) + $d[3]) * $q) + $d[4]) * $q) + 1);
				}
			}
		}

		return self::$_errorCodes['null'];
	}

	static private function _inverse_ncdf2($prob)
	{
		$a1 = 2.5066282388399999;
		$a2 = -18.615000625290001;
		$a3 = 41.39119773534;
		$a4 = -25.44106049637;
		$b1 = -8.4735109308999998;
		$b2 = 23.083367437429999;
		$b3 = -21.06224101826;
		$b4 = 3.13082909833;
		$c1 = 0.33747548227261498;
		$c2 = 0.97616901909171905;
		$c3 = 0.16079797149182101;
		$c4 = 0.027643881033386299;
		$c5 = 0.0038405729373608998;
		$c6 = 0.00039518965119189999;
		$c7 = 3.2176788176800002E-5;
		$c8 = 2.8881673640000001E-7;
		$c9 = 3.9603151870000003E-7;
		$y = $prob - 0.5;

		if (abs($y) < 0.41999999999999998) {
			$z = $y * $y;
			$z = ($y * (((((($a4 * $z) + $a3) * $z) + $a2) * $z) + $a1)) / (((((((($b4 * $z) + $b3) * $z) + $b2) * $z) + $b1) * $z) + 1);
		}
		else {
			if (0 < $y) {
				$z = log(0 - log(1 - $prob));
			}
			else {
				$z = log(0 - log($prob));
			}

			$z = $c1 + ($z * ($c2 + ($z * ($c3 + ($z * ($c4 + ($z * ($c5 + ($z * ($c6 + ($z * ($c7 + ($z * ($c8 + ($z * $c9)))))))))))))));

			if ($y < 0) {
				$z = 0 - $z;
			}
		}

		return $z;
	}

	static private function _inverse_ncdf3($p)
	{
		$split1 = 0.42499999999999999;
		$split2 = 5;
		$const1 = 0.18062500000000001;
		$const2 = 1.6000000000000001;
		$a0 = 3.3871328727963665;
		$a1 = 133.14166789178438;
		$a2 = 1971.5909503065513;
		$a3 = 13731.693765509461;
		$a4 = 45921.95393154987;
		$a5 = 67265.770927008707;
		$a6 = 33430.575583588128;
		$a7 = 2509.0809287301227;
		$b1 = 42.313330701600911;
		$b2 = 687.18700749205789;
		$b3 = 5394.1960214247511;
		$b4 = 21213.794301586597;
		$b5 = 39307.895800092709;
		$b6 = 28729.085735721943;
		$b7 = 5226.4952788528544;
		$c0 = 1.4234371107496835;
		$c1 = 4.6303378461565456;
		$c2 = 5.769497221460691;
		$c3 = 3.6478483247632045;
		$c4 = 1.2704582524523684;
		$c5 = 0.24178072517745061;
		$c6 = 0.022723844989269184;
		$c7 = 0.00077454501427834139;
		$d1 = 2.053191626637759;
		$d2 = 1.6763848301838038;
		$d3 = 0.68976733498510001;
		$d4 = 0.14810397642748008;
		$d5 = 0.015198666563616457;
		$d6 = 0.00054759380849953455;
		$d7 = 1.0507500716444169E-9;
		$e0 = 6.6579046435011033;
		$e1 = 5.4637849111641144;
		$e2 = 1.7848265399172913;
		$e3 = 0.29656057182850487;
		$e4 = 0.026532189526576124;
		$e5 = 0.0012426609473880784;
		$e6 = 2.7115555687434876E-5;
		$e7 = 2.0103343992922881E-7;
		$f1 = 0.59983220655588798;
		$f2 = 0.13692988092273581;
		$f3 = 0.014875361290850615;
		$f4 = 0.00078686913114561329;
		$f5 = 1.8463183175100548E-5;
		$f6 = 1.4215117583164459E-7;
		$f7 = 2.0442631033899397E-15;
		$q = $p - 0.5;

		if (abs($q) <= split1) {
			$R = $const1 - ($q * $q);
			$z = ($q * (((((((((((((($a7 * $R) + $a6) * $R) + $a5) * $R) + $a4) * $R) + $a3) * $R) + $a2) * $R) + $a1) * $R) + $a0)) / (((((((((((((($b7 * $R) + $b6) * $R) + $b5) * $R) + $b4) * $R) + $b3) * $R) + $b2) * $R) + $b1) * $R) + 1);
		}
		else {
			if ($q < 0) {
				$R = $p;
			}
			else {
				$R = 1 - $p;
			}

			$R = pow(0 - log($R), 2);

			if ($R <= $split2) {
				$R = $R - $const2;
				$z = (((((((((((((($c7 * $R) + $c6) * $R) + $c5) * $R) + $c4) * $R) + $c3) * $R) + $c2) * $R) + $c1) * $R) + $c0) / (((((((((((((($d7 * $R) + $d6) * $R) + $d5) * $R) + $d4) * $R) + $d3) * $R) + $d2) * $R) + $d1) * $R) + 1);
			}
			else {
				$R = $R - $split2;
				$z = (((((((((((((($e7 * $R) + $e6) * $R) + $e5) * $R) + $e4) * $R) + $e3) * $R) + $e2) * $R) + $e1) * $R) + $e0) / (((((((((((((($f7 * $R) + $f6) * $R) + $f5) * $R) + $f4) * $R) + $f3) * $R) + $f2) * $R) + $f1) * $R) + 1);
			}

			if ($q < 0) {
				$z = 0 - $z;
			}
		}

		return $z;
	}

	static public function NORMINV($probability, $mean, $stdDev)
	{
		$probability = self::flattenSingleValue($probability);
		$mean = self::flattenSingleValue($mean);
		$stdDev = self::flattenSingleValue($stdDev);
		if (is_numeric($probability) && is_numeric($mean) && is_numeric($stdDev)) {
			if (($probability < 0) || (1 < $probability)) {
				return self::$_errorCodes['num'];
			}

			if ($stdDev < 0) {
				return self::$_errorCodes['num'];
			}

			return (self::_inverse_ncdf($probability) * $stdDev) + $mean;
		}

		return self::$_errorCodes['value'];
	}

	static public function NORMSINV($value)
	{
		return self::NORMINV($value, 0, 1);
	}

	static public function LOGINV($probability, $mean, $stdDev)
	{
		$probability = self::flattenSingleValue($probability);
		$mean = self::flattenSingleValue($mean);
		$stdDev = self::flattenSingleValue($stdDev);
		if (is_numeric($probability) && is_numeric($mean) && is_numeric($stdDev)) {
			if (($probability < 0) || (1 < $probability) || ($stdDev <= 0)) {
				return self::$_errorCodes['num'];
			}

			return exp($mean + ($stdDev * self::NORMSINV($probability)));
		}

		return self::$_errorCodes['value'];
	}

	static public function HYPGEOMDIST($sampleSuccesses, $sampleNumber, $populationSuccesses, $populationNumber)
	{
		$sampleSuccesses = floor(self::flattenSingleValue($sampleSuccesses));
		$sampleNumber = floor(self::flattenSingleValue($sampleNumber));
		$populationSuccesses = floor(self::flattenSingleValue($populationSuccesses));
		$populationNumber = floor(self::flattenSingleValue($populationNumber));
		if (is_numeric($sampleSuccesses) && is_numeric($sampleNumber) && is_numeric($populationSuccesses) && is_numeric($populationNumber)) {
			if (($sampleSuccesses < 0) || ($sampleNumber < $sampleSuccesses) || ($populationSuccesses < $sampleSuccesses)) {
				return self::$_errorCodes['num'];
			}

			if (($sampleNumber <= 0) || ($populationNumber < $sampleNumber)) {
				return self::$_errorCodes['num'];
			}

			if (($populationSuccesses <= 0) || ($populationNumber < $populationSuccesses)) {
				return self::$_errorCodes['num'];
			}

			return (self::COMBIN($populationSuccesses, $sampleSuccesses) * self::COMBIN($populationNumber - $populationSuccesses, $sampleNumber - $sampleSuccesses)) / self::COMBIN($populationNumber, $sampleNumber);
		}

		return self::$_errorCodes['value'];
	}

	static public function TDIST($value, $degrees, $tails)
	{
		$value = self::flattenSingleValue($value);
		$degrees = floor(self::flattenSingleValue($degrees));
		$tails = floor(self::flattenSingleValue($tails));
		if (is_numeric($value) && is_numeric($degrees) && is_numeric($tails)) {
			if (($value < 0) || ($degrees < 1) || ($tails < 1) || (2 < $tails)) {
				return self::$_errorCodes['num'];
			}

			$tterm = $degrees;
			$ttheta = atan2($value, sqrt($tterm));
			$tc = cos($ttheta);
			$ts = sin($ttheta);
			$tsum = 0;

			if (($degrees % 2) == 1) {
				$ti = 3;
				$tterm = $tc;
			}
			else {
				$ti = 2;
				$tterm = 1;
			}

			$tsum = $tterm;

			while ($ti < $degrees) {
				$tterm *= ($tc * $tc * ($ti - 1)) / $ti;
				$tsum += $tterm;
				$ti += 2;
			}

			$tsum *= $ts;

			if (($degrees % 2) == 1) {
				$tsum = M_2DIVPI * ($tsum + $ttheta);
			}

			$tValue = 0.5 * (1 + $tsum);

			if ($tails == 1) {
				return 1 - abs($tValue);
			}
			else {
				return 1 - abs(1 - $tValue - $tValue);
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function TINV($probability, $degrees)
	{
		$probability = self::flattenSingleValue($probability);
		$degrees = floor(self::flattenSingleValue($degrees));
		if (is_numeric($probability) && is_numeric($degrees)) {
			$xLo = 100;
			$xHi = 0;
			$x = $xNew = 1;
			$dx = 1;
			$i = 0;

			while ($i++ < MAX_ITERATIONS) {
				$result = self::TDIST($x, $degrees, 2);
				$error = $result - $probability;

				if ($error == 0) {
					$dx = 0;
				}
				else if ($error < 0) {
					$xLo = $x;
				}
				else {
					$xHi = $x;
				}

				if ($result != 0) {
					$dx = $error / $result;
					$xNew = $x - $dx;
				}

				if (($xNew < $xLo) || ($xHi < $xNew) || ($result == 0)) {
					$xNew = ($xLo + $xHi) / 2;
					$dx = $xNew - $x;
				}

				$x = $xNew;
			}

			if ($i == MAX_ITERATIONS) {
				return self::$_errorCodes['na'];
			}

			return round($x, 12);
		}

		return self::$_errorCodes['value'];
	}

	static public function CONFIDENCE($alpha, $stdDev, $size)
	{
		$alpha = self::flattenSingleValue($alpha);
		$stdDev = self::flattenSingleValue($stdDev);
		$size = floor(self::flattenSingleValue($size));
		if (is_numeric($alpha) && is_numeric($stdDev) && is_numeric($size)) {
			if (($alpha <= 0) || (1 <= $alpha)) {
				return self::$_errorCodes['num'];
			}

			if (($stdDev <= 0) || ($size < 1)) {
				return self::$_errorCodes['num'];
			}

			return (self::NORMSINV(1 - ($alpha / 2)) * $stdDev) / sqrt($size);
		}

		return self::$_errorCodes['value'];
	}

	static public function POISSON($value, $mean, $cumulative)
	{
		$value = self::flattenSingleValue($value);
		$mean = self::flattenSingleValue($mean);
		if (is_numeric($value) && is_numeric($mean)) {
			if (($value <= 0) || ($mean <= 0)) {
				return self::$_errorCodes['num'];
			}

			if (is_numeric($cumulative) || is_bool($cumulative)) {
				if ($cumulative) {
					$summer = 0;

					for ($i = 0; $i <= floor($value); ++$i) {
						$summer += pow($mean, $i) / self::FACT($i);
					}

					return exp(0 - $mean) * $summer;
				}
				else {
					return (exp(0 - $mean) * pow($mean, $value)) / self::FACT($value);
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function WEIBULL($value, $alpha, $beta, $cumulative)
	{
		$value = self::flattenSingleValue($value);
		$alpha = self::flattenSingleValue($alpha);
		$beta = self::flattenSingleValue($beta);
		if (is_numeric($value) && is_numeric($alpha) && is_numeric($beta)) {
			if (($value < 0) || ($alpha <= 0) || ($beta <= 0)) {
				return self::$_errorCodes['num'];
			}

			if (is_numeric($cumulative) || is_bool($cumulative)) {
				if ($cumulative) {
					return 1 - exp(0 - pow($value / $beta, $alpha));
				}
				else {
					return ($alpha / pow($beta, $alpha)) * pow($value, $alpha - 1) * exp(0 - pow($value / $beta, $alpha));
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function ZTEST($dataSet, $m0, $sigma = NULL)
	{
		$dataSet = self::flattenArrayIndexed($dataSet);
		$m0 = self::flattenSingleValue($m0);
		$sigma = self::flattenSingleValue($sigma);

		if (is_null($sigma)) {
			$sigma = self::STDEV($dataSet);
		}

		$n = count($dataSet);
		return 1 - self::NORMSDIST((self::AVERAGE($dataSet) - $m0) / $sigma / SQRT($n));
	}

	static public function SKEW()
	{
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$mean = self::AVERAGE($aArgs);
		$stdDev = self::STDEV($aArgs);
		$count = $summer = 0;

		foreach ($aArgs as $k => $arg) {
			if (is_bool($arg) && !self::isMatrixValue($k)) {
			}
			else {
				if (is_numeric($arg) && !is_string($arg)) {
					$summer += pow(($arg - $mean) / $stdDev, 3);
					++$count;
				}
			}
		}

		if (2 < $count) {
			return $summer * ($count / (($count - 1) * ($count - 2)));
		}

		return self::$_errorCodes['divisionbyzero'];
	}

	static public function KURT()
	{
		$aArgs = self::flattenArrayIndexed(func_get_args());
		$mean = self::AVERAGE($aArgs);
		$stdDev = self::STDEV($aArgs);

		if (0 < $stdDev) {
			$count = $summer = 0;

			foreach ($aArgs as $k => $arg) {
				if (is_bool($arg) && !self::isMatrixValue($k)) {
				}
				else {
					if (is_numeric($arg) && !is_string($arg)) {
						$summer += pow(($arg - $mean) / $stdDev, 4);
						++$count;
					}
				}
			}

			if (3 < $count) {
				return ($summer * (($count * ($count + 1)) / (($count - 1) * ($count - 2) * ($count - 3)))) - ((3 * pow($count - 1, 2)) / (($count - 2) * ($count - 3)));
			}
		}

		return self::$_errorCodes['divisionbyzero'];
	}

	static public function RAND($min = 0, $max = 0)
	{
		$min = self::flattenSingleValue($min);
		$max = self::flattenSingleValue($max);
		if (($min == 0) && ($max == 0)) {
			return rand(0, 10000000) / 10000000;
		}
		else {
			return rand($min, $max);
		}
	}

	static public function MOD($a = 1, $b = 1)
	{
		$a = self::flattenSingleValue($a);
		$b = self::flattenSingleValue($b);

		if ($b == 0) {
			return self::$_errorCodes['divisionbyzero'];
		}
		else {
			if (($a < 0) && (0 < $b)) {
				return $b - fmod(abs($a), $b);
			}
			else {
				if ((0 < $a) && ($b < 0)) {
					return $b + fmod($a, abs($b));
				}
			}
		}

		return fmod($a, $b);
	}

	static public function CHARACTER($character)
	{
		$character = self::flattenSingleValue($character);
		if (!is_numeric($character) || ($character < 0)) {
			return self::$_errorCodes['value'];
		}

		if (function_exists('mb_convert_encoding')) {
			return mb_convert_encoding('&#' . intval($character) . ';', 'UTF-8', 'HTML-ENTITIES');
		}
		else {
			return chr(intval($character));
		}
	}

	static private function _uniord($c)
	{
		if ((0 <= ord($c[0])) && (ord($c[0]) <= 127)) {
			return ord($c[0]);
		}

		if ((192 <= ord($c[0])) && (ord($c[0]) <= 223)) {
			return ((ord($c[0]) - 192) * 64) + (ord($c[1]) - 128);
		}

		if ((224 <= ord($c[0])) && (ord($c[0]) <= 239)) {
			return ((ord($c[0]) - 224) * 4096) + ((ord($c[1]) - 128) * 64) + (ord($c[2]) - 128);
		}

		if ((240 <= ord($c[0])) && (ord($c[0]) <= 247)) {
			return ((ord($c[0]) - 240) * 262144) + ((ord($c[1]) - 128) * 4096) + ((ord($c[2]) - 128) * 64) + (ord($c[3]) - 128);
		}

		if ((248 <= ord($c[0])) && (ord($c[0]) <= 251)) {
			return ((ord($c[0]) - 248) * 16777216) + ((ord($c[1]) - 128) * 262144) + ((ord($c[2]) - 128) * 4096) + ((ord($c[3]) - 128) * 64) + (ord($c[4]) - 128);
		}

		if ((252 <= ord($c[0])) && (ord($c[0]) <= 253)) {
			return ((ord($c[0]) - 252) * 1073741824) + ((ord($c[1]) - 128) * 16777216) + ((ord($c[2]) - 128) * 262144) + ((ord($c[3]) - 128) * 4096) + ((ord($c[4]) - 128) * 64) + (ord($c[5]) - 128);
		}

		if ((254 <= ord($c[0])) && (ord($c[0]) <= 255)) {
			return self::$_errorCodes['value'];
		}

		return 0;
	}

	static public function ASCIICODE($characters)
	{
		$characters = self::flattenSingleValue($characters);

		if (is_bool($characters)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				$characters = (int) $characters;
			}
			else if ($characters) {
				$characters = 'True';
			}
			else {
				$characters = 'False';
			}
		}

		$character = $characters;
		if (function_exists('mb_strlen') && function_exists('mb_substr')) {
			if (1 < mb_strlen($characters, 'UTF-8')) {
				$character = mb_substr($characters, 0, 1, 'UTF-8');
			}

			return self::_uniord($character);
		}
		else {
			if (0 < strlen($characters)) {
				$character = substr($characters, 0, 1);
			}

			return ord($character);
		}
	}

	static public function CONCATENATE()
	{
		$returnValue = '';
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			if (is_bool($arg)) {
				if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
					$arg = (int) $arg;
				}
				else if ($arg) {
					$arg = 'TRUE';
				}
				else {
					$arg = 'FALSE';
				}
			}

			$returnValue .= $arg;
		}

		return $returnValue;
	}

	static public function STRINGLENGTH($value = '')
	{
		$value = self::flattenSingleValue($value);

		if (is_bool($value)) {
			$value = ($value ? 'TRUE' : 'FALSE');
		}

		if (function_exists('mb_strlen')) {
			return mb_strlen($value, 'UTF-8');
		}
		else {
			return strlen($value);
		}
	}

	static public function SEARCHSENSITIVE($needle, $haystack, $offset = 1)
	{
		$needle = self::flattenSingleValue($needle);
		$haystack = self::flattenSingleValue($haystack);
		$offset = self::flattenSingleValue($offset);

		if (!is_bool($needle)) {
			if (is_bool($haystack)) {
				$haystack = ($haystack ? 'TRUE' : 'FALSE');
			}

			if ((0 < $offset) && ($offset < strlen($haystack))) {
				if (function_exists('mb_strpos')) {
					$pos = mb_strpos($haystack, $needle, --$offset, 'UTF-8');
				}
				else {
					$pos = strpos($haystack, $needle, --$offset);
				}

				if ($pos !== false) {
					return ++$pos;
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function SEARCHINSENSITIVE($needle, $haystack, $offset = 1)
	{
		$needle = self::flattenSingleValue($needle);
		$haystack = self::flattenSingleValue($haystack);
		$offset = self::flattenSingleValue($offset);

		if (!is_bool($needle)) {
			if (is_bool($haystack)) {
				$haystack = ($haystack ? 'TRUE' : 'FALSE');
			}

			if ((0 < $offset) && ($offset < strlen($haystack))) {
				if (function_exists('mb_stripos')) {
					$pos = mb_stripos($haystack, $needle, --$offset, 'UTF-8');
				}
				else {
					$pos = stripos($haystack, $needle, --$offset);
				}

				if ($pos !== false) {
					return ++$pos;
				}
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function LEFT($value = '', $chars = 1)
	{
		$value = self::flattenSingleValue($value);
		$chars = self::flattenSingleValue($chars);

		if ($chars < 0) {
			return self::$_errorCodes['value'];
		}

		if (is_bool($value)) {
			$value = ($value ? 'TRUE' : 'FALSE');
		}

		if (function_exists('mb_substr')) {
			return mb_substr($value, 0, $chars, 'UTF-8');
		}
		else {
			return substr($value, 0, $chars);
		}
	}

	static public function RIGHT($value = '', $chars = 1)
	{
		$value = self::flattenSingleValue($value);
		$chars = self::flattenSingleValue($chars);

		if ($chars < 0) {
			return self::$_errorCodes['value'];
		}

		if (is_bool($value)) {
			$value = ($value ? 'TRUE' : 'FALSE');
		}

		if (function_exists('mb_substr') && function_exists('mb_strlen')) {
			return mb_substr($value, mb_strlen($value, 'UTF-8') - $chars, $chars, 'UTF-8');
		}
		else {
			return substr($value, strlen($value) - $chars);
		}
	}

	static public function MID($value = '', $start = 1, $chars = NULL)
	{
		$value = self::flattenSingleValue($value);
		$start = self::flattenSingleValue($start);
		$chars = self::flattenSingleValue($chars);
		if (($start < 1) || ($chars < 0)) {
			return self::$_errorCodes['value'];
		}

		if (is_bool($value)) {
			$value = ($value ? 'TRUE' : 'FALSE');
		}

		if (function_exists('mb_substr')) {
			return mb_substr($value, --$start, $chars, 'UTF-8');
		}
		else {
			return substr($value, --$start, $chars);
		}
	}

	static public function REPLACE($oldText = '', $start = 1, $chars = NULL, $newText)
	{
		$oldText = self::flattenSingleValue($oldText);
		$start = self::flattenSingleValue($start);
		$chars = self::flattenSingleValue($chars);
		$newText = self::flattenSingleValue($newText);
		$left = self::LEFT($oldText, $start - 1);
		$right = self::RIGHT($oldText, (self::STRINGLENGTH($oldText) - ($start + $chars)) + 1);
		return $left . $newText . $right;
	}

	static public function SUBSTITUTE($text = '', $fromText = '', $toText = '', $instance = 0)
	{
		$text = self::flattenSingleValue($text);
		$fromText = self::flattenSingleValue($fromText);
		$toText = self::flattenSingleValue($toText);
		$instance = floor(self::flattenSingleValue($instance));

		if ($instance == 0) {
			if (function_exists('mb_str_replace')) {
				return mb_str_replace($fromText, $toText, $text);
			}
			else {
				return str_replace($fromText, $toText, $text);
			}
		}
		else {
			$pos = -1;

			while (0 < $instance) {
				if (function_exists('mb_strpos')) {
					$pos = mb_strpos($text, $fromText, $pos + 1, 'UTF-8');
				}
				else {
					$pos = strpos($text, $fromText, $pos + 1);
				}

				if ($pos === false) {
					break;
				}

				--$instance;
			}

			if ($pos !== false) {
				if (function_exists('mb_strlen')) {
					return self::REPLACE($text, ++$pos, mb_strlen($fromText, 'UTF-8'), $toText);
				}
				else {
					return self::REPLACE($text, ++$pos, strlen($fromText), $toText);
				}
			}
		}

		return $left . $newText . $right;
	}

	static public function RETURNSTRING($testValue = '')
	{
		$testValue = self::flattenSingleValue($testValue);

		if (is_string($testValue)) {
			return $testValue;
		}

		return NULL;
	}

	static public function FIXEDFORMAT($value, $decimals = 2, $no_commas = false)
	{
		$value = self::flattenSingleValue($value);
		$decimals = self::flattenSingleValue($decimals);
		$no_commas = self::flattenSingleValue($no_commas);
		$valueResult = round($value, $decimals);

		if ($decimals < 0) {
			$decimals = 0;
		}

		if (!$no_commas) {
			$valueResult = number_format($valueResult, $decimals);
		}

		return (string) $valueResult;
	}

	static public function TEXTFORMAT($value, $format)
	{
		$value = self::flattenSingleValue($value);
		$format = self::flattenSingleValue($format);
		if (is_string($value) && !is_numeric($value) && PHPExcel_Shared_Date::isDateTimeFormatCode($format)) {
			$value = self::DATEVALUE($value);
		}

		return (string) PHPExcel_Style_NumberFormat::toFormattedString($value, $format);
	}

	static public function TRIMSPACES($stringValue = '')
	{
		$stringValue = self::flattenSingleValue($stringValue);
		if (is_string($stringValue) || is_numeric($stringValue)) {
			return trim(preg_replace('/  +/', ' ', $stringValue));
		}

		return NULL;
	}

	static public function TRIMNONPRINTABLE($stringValue = '')
	{
		$stringValue = self::flattenSingleValue($stringValue);

		if (is_bool($stringValue)) {
			$stringValue = ($stringValue ? 'TRUE' : 'FALSE');
		}

		if (self::$_invalidChars == NULL) {
			self::$_invalidChars = range(chr(0), chr(31));
		}

		if (is_string($stringValue) || is_numeric($stringValue)) {
			return str_replace(self::$_invalidChars, '', trim($stringValue, "\x00..\x1f"));
		}

		return NULL;
	}

	static public function ERROR_TYPE($value = '')
	{
		$value = self::flattenSingleValue($value);
		$i = 1;

		foreach (self::$_errorCodes as $errorCode) {
			if ($value == $errorCode) {
				return $i;
			}

			++$i;
		}

		return self::$_errorCodes['na'];
	}

	static public function IS_BLANK($value = NULL)
	{
		if (!is_null($value)) {
			$value = self::flattenSingleValue($value);
		}

		return is_null($value);
	}

	static public function IS_ERR($value = '')
	{
		$value = self::flattenSingleValue($value);
		return self::IS_ERROR($value) && !self::IS_NA($value);
	}

	static public function IS_ERROR($value = '')
	{
		$value = self::flattenSingleValue($value);
		return in_array($value, array_values(self::$_errorCodes));
	}

	static public function IS_NA($value = '')
	{
		$value = self::flattenSingleValue($value);
		return $value === self::$_errorCodes['na'];
	}

	static public function IS_EVEN($value = 0)
	{
		$value = self::flattenSingleValue($value);
		if (is_bool($value) || (is_string($value) && !is_numeric($value))) {
			return self::$_errorCodes['value'];
		}

		return ($value % 2) == 0;
	}

	static public function IS_ODD($value = NULL)
	{
		$value = self::flattenSingleValue($value);
		if (is_bool($value) || (is_string($value) && !is_numeric($value))) {
			return self::$_errorCodes['value'];
		}

		return (abs($value) % 2) == 1;
	}

	static public function IS_NUMBER($value = 0)
	{
		$value = self::flattenSingleValue($value);

		if (is_string($value)) {
			return false;
		}

		return is_numeric($value);
	}

	static public function IS_LOGICAL($value = true)
	{
		$value = self::flattenSingleValue($value);
		return is_bool($value);
	}

	static public function IS_TEXT($value = '')
	{
		$value = self::flattenSingleValue($value);
		return is_string($value);
	}

	static public function IS_NONTEXT($value = '')
	{
		return !self::IS_TEXT($value);
	}

	static public function VERSION()
	{
		return 'PHPExcel 1.7.3c, 2010-06-01';
	}

	static public function DATE($year = 0, $month = 1, $day = 1)
	{
		$year = (int) self::flattenSingleValue($year);
		$month = (int) self::flattenSingleValue($month);
		$day = (int) self::flattenSingleValue($day);
		$baseYear = PHPExcel_Shared_Date::getExcelCalendar();

		if ($year < ($baseYear - 1900)) {
			return self::$_errorCodes['num'];
		}

		if ((($baseYear - 1900) != 0) && ($year < $baseYear) && (1900 <= $year)) {
			return self::$_errorCodes['num'];
		}

		if (($year < $baseYear) && (($baseYear - 1900) <= $year)) {
			$year += 1900;
		}

		if ($month < 1) {
			--$month;
			$year += ceil($month / 12) - 1;
			$month = 13 - abs($month % 12);
		}
		else if (12 < $month) {
			$year += floor($month / 12);
			$month = $month % 12;
		}

		if (($year < $baseYear) || (10000 <= $year)) {
			return self::$_errorCodes['num'];
		}

		$excelDateValue = PHPExcel_Shared_Date::FormattedPHPToExcel($year, $month, $day);

		switch (self::getReturnDateType()) {
		case self::RETURNDATE_EXCEL:
			return (double) $excelDateValue;
			break;

		case self::RETURNDATE_PHP_NUMERIC:
			return (int) PHPExcel_Shared_Date::ExcelToPHP($excelDateValue);
			break;

		case self::RETURNDATE_PHP_OBJECT:
			return PHPExcel_Shared_Date::ExcelToPHPObject($excelDateValue);
			break;
		}
	}

	static public function TIME($hour = 0, $minute = 0, $second = 0)
	{
		$hour = self::flattenSingleValue($hour);
		$minute = self::flattenSingleValue($minute);
		$second = self::flattenSingleValue($second);

		if ($hour == '') {
			$hour = 0;
		}

		if ($minute == '') {
			$minute = 0;
		}

		if ($second == '') {
			$second = 0;
		}

		if (!is_numeric($hour) || !is_numeric($minute) || !is_numeric($second)) {
			return self::$_errorCodes['value'];
		}

		$hour = (int) $hour;
		$minute = (int) $minute;
		$second = (int) $second;

		if ($second < 0) {
			$minute += floor($second / 60);
			$second = 60 - abs($second % 60);

			if ($second == 60) {
				$second = 0;
			}
		}
		else if (60 <= $second) {
			$minute += floor($second / 60);
			$second = $second % 60;
		}

		if ($minute < 0) {
			$hour += floor($minute / 60);
			$minute = 60 - abs($minute % 60);

			if ($minute == 60) {
				$minute = 0;
			}
		}
		else if (60 <= $minute) {
			$hour += floor($minute / 60);
			$minute = $minute % 60;
		}

		if (23 < $hour) {
			$hour = $hour % 24;
		}
		else if ($hour < 0) {
			return self::$_errorCodes['num'];
		}

		switch (self::getReturnDateType()) {
		case self::RETURNDATE_EXCEL:
			$date = 0;
			$calendar = PHPExcel_Shared_Date::getExcelCalendar();

			if ($calendar != PHPExcel_Shared_Date::CALENDAR_WINDOWS_1900) {
				$date = 1;
			}

			return (double) PHPExcel_Shared_Date::FormattedPHPToExcel($calendar, 1, $date, $hour, $minute, $second);
			break;

		case self::RETURNDATE_PHP_NUMERIC:
			return (int) PHPExcel_Shared_Date::ExcelToPHP(PHPExcel_Shared_Date::FormattedPHPToExcel(1970, 1, 1, $hour - 1, $minute, $second));
			break;

		case self::RETURNDATE_PHP_OBJECT:
			$dayAdjust = 0;

			if ($hour < 0) {
				$dayAdjust = floor($hour / 24);
				$hour = 24 - abs($hour % 24);

				if ($hour == 24) {
					$hour = 0;
				}
			}
			else if (24 <= $hour) {
				$dayAdjust = floor($hour / 24);
				$hour = $hour % 24;
			}

			$phpDateObject = new DateTime('1900-01-01 ' . $hour . ':' . $minute . ':' . $second);

			if ($dayAdjust != 0) {
				$phpDateObject->modify($dayAdjust . ' days');
			}

			return $phpDateObject;
			break;
		}
	}

	static public function DATEVALUE($dateValue = 1)
	{
		$dateValue = trim(self::flattenSingleValue($dateValue), '"');
		$dateValue = preg_replace('/(\\d)(st|nd|rd|th)([ -\\/])/Ui', '$1$3', $dateValue);
		$dateValue = str_replace(array('/', '.', '-', '  '), array(' ', ' ', ' ', ' '), $dateValue);
		$yearFound = false;
		$t1 = explode(' ', $dateValue);

		foreach ($t1 as &$t) {
			if (is_numeric($t) && (31 < $t)) {
				if ($yearFound) {
					return self::$_errorCodes['value'];
				}
				else {
					if ($t < 100) {
						$t += 1900;
					}

					$yearFound = true;
				}
			}
		}

		if ((count($t1) == 1) && (strpos($t, ':') != false)) {
			return 0;
		}
		else if (count($t1) == 2) {
			if ($yearFound) {
				array_unshift($t1, 1);
			}
			else {
				array_push($t1, date('Y'));
			}
		}

		unset($t);
		$dateValue = implode(' ', $t1);
		$PHPDateArray = date_parse($dateValue);
		if (($PHPDateArray === false) || (0 < $PHPDateArray['error_count'])) {
			$testVal1 = strtok($dateValue, '- ');

			if ($testVal1 !== false) {
				$testVal2 = strtok('- ');

				if ($testVal2 !== false) {
					$testVal3 = strtok('- ');

					if ($testVal3 === false) {
						$testVal3 = strftime('%Y');
					}
				}
				else {
					return self::$_errorCodes['value'];
				}
			}
			else {
				return self::$_errorCodes['value'];
			}

			$PHPDateArray = date_parse($testVal1 . '-' . $testVal2 . '-' . $testVal3);
			if (($PHPDateArray === false) || (0 < $PHPDateArray['error_count'])) {
				$PHPDateArray = date_parse($testVal2 . '-' . $testVal1 . '-' . $testVal3);
				if (($PHPDateArray === false) || (0 < $PHPDateArray['error_count'])) {
					return self::$_errorCodes['value'];
				}
			}
		}

		if (($PHPDateArray !== false) && ($PHPDateArray['error_count'] == 0)) {
			if ($PHPDateArray['year'] == '') {
				$PHPDateArray['year'] = strftime('%Y');
			}

			if ($PHPDateArray['month'] == '') {
				$PHPDateArray['month'] = strftime('%m');
			}

			if ($PHPDateArray['day'] == '') {
				$PHPDateArray['day'] = strftime('%d');
			}

			$excelDateValue = floor(PHPExcel_Shared_Date::FormattedPHPToExcel($PHPDateArray['year'], $PHPDateArray['month'], $PHPDateArray['day'], $PHPDateArray['hour'], $PHPDateArray['minute'], $PHPDateArray['second']));

			switch (self::getReturnDateType()) {
			case self::RETURNDATE_EXCEL:
				return (double) $excelDateValue;
				break;

			case self::RETURNDATE_PHP_NUMERIC:
				return (int) PHPExcel_Shared_Date::ExcelToPHP($excelDateValue);
				break;

			case self::RETURNDATE_PHP_OBJECT:
				return new DateTime($PHPDateArray['year'] . '-' . $PHPDateArray['month'] . '-' . $PHPDateArray['day'] . ' 00:00:00');
				break;
			}
		}

		return self::$_errorCodes['value'];
	}

	static private function _getDateValue($dateValue)
	{
		if (!is_numeric($dateValue)) {
			if (is_string($dateValue) && (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC)) {
				return self::$_errorCodes['value'];
			}

			if (is_object($dateValue) && $dateValue instanceof PHPExcel_Shared_Date::$dateTimeObjectType) {
				$dateValue = PHPExcel_Shared_Date::PHPToExcel($dateValue);
			}
			else {
				$saveReturnDateType = self::getReturnDateType();
				self::setReturnDateType(self::RETURNDATE_EXCEL);
				$dateValue = self::DATEVALUE($dateValue);
				self::setReturnDateType($saveReturnDateType);
			}
		}

		return $dateValue;
	}

	static public function TIMEVALUE($timeValue)
	{
		$timeValue = trim(self::flattenSingleValue($timeValue), '"');
		$timeValue = str_replace(array('/', '.'), array('-', '-'), $timeValue);
		$PHPDateArray = date_parse($timeValue);
		if (($PHPDateArray !== false) && ($PHPDateArray['error_count'] == 0)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				$excelDateValue = PHPExcel_Shared_Date::FormattedPHPToExcel($PHPDateArray['year'], $PHPDateArray['month'], $PHPDateArray['day'], $PHPDateArray['hour'], $PHPDateArray['minute'], $PHPDateArray['second']);
			}
			else {
				$excelDateValue = PHPExcel_Shared_Date::FormattedPHPToExcel(1900, 1, 1, $PHPDateArray['hour'], $PHPDateArray['minute'], $PHPDateArray['second']) - 1;
			}

			switch (self::getReturnDateType()) {
			case self::RETURNDATE_EXCEL:
				return (double) $excelDateValue;
				break;

			case self::RETURNDATE_PHP_NUMERIC:
				return (int) ($phpDateValue = PHPExcel_Shared_Date::ExcelToPHP($excelDateValue + 25569) - 3600);
				break;

			case self::RETURNDATE_PHP_OBJECT:
				return new DateTime('1900-01-01 ' . $PHPDateArray['hour'] . ':' . $PHPDateArray['minute'] . ':' . $PHPDateArray['second']);
				break;
			}
		}

		return self::$_errorCodes['value'];
	}

	static private function _getTimeValue($timeValue)
	{
		$saveReturnDateType = self::getReturnDateType();
		self::setReturnDateType(self::RETURNDATE_EXCEL);
		$timeValue = self::TIMEVALUE($timeValue);
		self::setReturnDateType($saveReturnDateType);
		return $timeValue;
	}

	static public function DATETIMENOW()
	{
		$saveTimeZone = date_default_timezone_get();
		date_default_timezone_set('UTC');
		$retValue = false;

		switch (self::getReturnDateType()) {
		case self::RETURNDATE_EXCEL:
			$retValue = (double) PHPExcel_Shared_Date::PHPToExcel(time());
			break;

		case self::RETURNDATE_PHP_NUMERIC:
			$retValue = (int) time();
			break;

		case self::RETURNDATE_PHP_OBJECT:
			$retValue = new DateTime();
			break;
		}

		date_default_timezone_set($saveTimeZone);
		return $retValue;
	}

	static public function DATENOW()
	{
		$saveTimeZone = date_default_timezone_get();
		date_default_timezone_set('UTC');
		$retValue = false;
		$excelDateTime = floor(PHPExcel_Shared_Date::PHPToExcel(time()));

		switch (self::getReturnDateType()) {
		case self::RETURNDATE_EXCEL:
			$retValue = (double) $excelDateTime;
			break;

		case self::RETURNDATE_PHP_NUMERIC:
			$retValue = (int) PHPExcel_Shared_Date::ExcelToPHP($excelDateTime) - 3600;
			break;

		case self::RETURNDATE_PHP_OBJECT:
			$retValue = PHPExcel_Shared_Date::ExcelToPHPObject($excelDateTime);
			break;
		}

		date_default_timezone_set($saveTimeZone);
		return $retValue;
	}

	static private function _isLeapYear($year)
	{
		return ((($year % 4) == 0) && (($year % 100) != 0)) || (($year % 400) == 0);
	}

	static private function _dateDiff360($startDay, $startMonth, $startYear, $endDay, $endMonth, $endYear, $methodUS)
	{
		if ($startDay == 31) {
			--$startDay;
		}
		else {
			if ($methodUS && ($startMonth == 2) && (($startDay == 29) || (($startDay == 28) && !self::_isLeapYear($startYear)))) {
				$startDay = 30;
			}
		}

		if ($endDay == 31) {
			if ($methodUS && ($startDay != 30)) {
				$endDay = 1;

				if ($endMonth == 12) {
					++$endYear;
					$endMonth = 1;
				}
				else {
					++$endMonth;
				}
			}
			else {
				$endDay = 30;
			}
		}

		return ($endDay + ($endMonth * 30) + ($endYear * 360)) - $startDay - ($startMonth * 30) - ($startYear * 360);
	}

	static public function DAYS360($startDate = 0, $endDate = 0, $method = false)
	{
		$startDate = self::flattenSingleValue($startDate);
		$endDate = self::flattenSingleValue($endDate);

		if (is_string($startDate = self::_getDateValue($startDate))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($endDate = self::_getDateValue($endDate))) {
			return self::$_errorCodes['value'];
		}

		$PHPStartDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($startDate);
		$startDay = $PHPStartDateObject->format('j');
		$startMonth = $PHPStartDateObject->format('n');
		$startYear = $PHPStartDateObject->format('Y');
		$PHPEndDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($endDate);
		$endDay = $PHPEndDateObject->format('j');
		$endMonth = $PHPEndDateObject->format('n');
		$endYear = $PHPEndDateObject->format('Y');
		return self::_dateDiff360($startDay, $startMonth, $startYear, $endDay, $endMonth, $endYear, !$method);
	}

	static public function DATEDIF($startDate = 0, $endDate = 0, $unit = 'D')
	{
		$startDate = self::flattenSingleValue($startDate);
		$endDate = self::flattenSingleValue($endDate);
		$unit = strtoupper(self::flattenSingleValue($unit));

		if (is_string($startDate = self::_getDateValue($startDate))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($endDate = self::_getDateValue($endDate))) {
			return self::$_errorCodes['value'];
		}

		if ($endDate <= $startDate) {
			return self::$_errorCodes['num'];
		}

		$difference = $endDate - $startDate;
		$PHPStartDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($startDate);
		$startDays = $PHPStartDateObject->format('j');
		$startMonths = $PHPStartDateObject->format('n');
		$startYears = $PHPStartDateObject->format('Y');
		$PHPEndDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($endDate);
		$endDays = $PHPEndDateObject->format('j');
		$endMonths = $PHPEndDateObject->format('n');
		$endYears = $PHPEndDateObject->format('Y');
		$retVal = self::$_errorCodes['num'];

		switch ($unit) {
		case 'D':
			$retVal = intval($difference);
			break;

		case 'M':
			$retVal = intval($endMonths - $startMonths) + (intval($endYears - $startYears) * 12);

			if ($endDays < $startDays) {
				--$retVal;
			}

			break;

		case 'Y':
			$retVal = intval($endYears - $startYears);

			if ($endMonths < $startMonths) {
				--$retVal;
			}
			else {
				if (($endMonths == $startMonths) && ($endDays < $startDays)) {
					--$retVal;
				}
			}

			break;

		case 'MD':
			if ($endDays < $startDays) {
				$retVal = $endDays;
				$PHPEndDateObject->modify('-' . $endDays . ' days');
				$adjustDays = $PHPEndDateObject->format('j');

				if ($startDays < $adjustDays) {
					$retVal += $adjustDays - $startDays;
				}
			}
			else {
				$retVal = $endDays - $startDays;
			}

			break;

		case 'YM':
			$retVal = intval($endMonths - $startMonths);

			if ($retVal < 0) {
				$retVal = 12 + $retVal;
			}

			if ($endDays < $startDays) {
				--$retVal;
			}

			break;

		case 'YD':
			$retVal = intval($difference);

			if ($startYears < $endYears) {
				while ($startYears < $endYears) {
					$PHPEndDateObject->modify('-1 year');
					$endYears = $PHPEndDateObject->format('Y');
				}

				$retVal = $PHPEndDateObject->format('z') - $PHPStartDateObject->format('z');

				if ($retVal < 0) {
					$retVal += 365;
				}
			}

			break;
		}

		return $retVal;
	}

	static public function YEARFRAC($startDate = 0, $endDate = 0, $method = 0)
	{
		$startDate = self::flattenSingleValue($startDate);
		$endDate = self::flattenSingleValue($endDate);
		$method = self::flattenSingleValue($method);

		if (is_string($startDate = self::_getDateValue($startDate))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($endDate = self::_getDateValue($endDate))) {
			return self::$_errorCodes['value'];
		}

		if ((is_numeric($method) && !is_string($method)) || ($method == '')) {
			switch ($method) {
			case 0:
				return self::DAYS360($startDate, $endDate) / 360;
				break;

			case 1:
				$days = self::DATEDIF($startDate, $endDate);
				$startYear = self::YEAR($startDate);
				$endYear = self::YEAR($endDate);
				$years = ($endYear - $startYear) + 1;
				$leapDays = 0;

				if ($years == 1) {
					if (self::_isLeapYear($endYear)) {
						$startMonth = self::MONTHOFYEAR($startDate);
						$endMonth = self::MONTHOFYEAR($endDate);
						$endDay = self::DAYOFMONTH($endDate);
						if (($startMonth < 3) || (((2 * 100) + 29) <= ($endMonth * 100) + $endDay)) {
							$leapDays += 1;
						}
					}
				}
				else {
					for ($year = $startYear; $year <= $endYear; ++$year) {
						if ($year == $startYear) {
							$startMonth = self::MONTHOFYEAR($startDate);
							$startDay = self::DAYOFMONTH($startDate);

							if ($startMonth < 3) {
								$leapDays += (self::_isLeapYear($year) ? 1 : 0);
							}
						}
						else if ($year == $endYear) {
							$endMonth = self::MONTHOFYEAR($endDate);
							$endDay = self::DAYOFMONTH($endDate);

							if (((2 * 100) + 29) <= ($endMonth * 100) + $endDay) {
								$leapDays += (self::_isLeapYear($year) ? 1 : 0);
							}
						}
						else {
							$leapDays += (self::_isLeapYear($year) ? 1 : 0);
						}
					}

					if ($years == 2) {
						if (($leapDays == 0) && self::_isLeapYear($startYear) && (365 < $days)) {
							$leapDays = 1;
						}
						else if ($days < 366) {
							$years = 1;
						}
					}

					$leapDays /= $years;
				}

				return $days / (365 + $leapDays);
				break;

			case 2:
				return self::DATEDIF($startDate, $endDate) / 360;
				break;

			case 3:
				return self::DATEDIF($startDate, $endDate) / 365;
				break;

			case 4:
				return self::DAYS360($startDate, $endDate, true) / 360;
				break;
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function NETWORKDAYS($startDate, $endDate)
	{
		$startDate = self::flattenSingleValue($startDate);
		$endDate = self::flattenSingleValue($endDate);
		$dateArgs = self::flattenArray(func_get_args());
		array_shift($dateArgs);
		array_shift($dateArgs);

		if (is_string($startDate = $sDate = self::_getDateValue($startDate))) {
			return self::$_errorCodes['value'];
		}

		$startDate = (double) floor($startDate);

		if (is_string($endDate = $eDate = self::_getDateValue($endDate))) {
			return self::$_errorCodes['value'];
		}

		$endDate = (double) floor($endDate);

		if ($eDate < $sDate) {
			$startDate = $eDate;
			$endDate = $sDate;
		}

		$startDoW = 6 - self::DAYOFWEEK($startDate, 2);

		if ($startDoW < 0) {
			$startDoW = 0;
		}

		$endDoW = self::DAYOFWEEK($endDate, 2);

		if (6 <= $endDoW) {
			$endDoW = 0;
		}

		$wholeWeekDays = floor(($endDate - $startDate) / 7) * 5;
		$partWeekDays = $endDoW + $startDoW;

		if (5 < $partWeekDays) {
			$partWeekDays -= 5;
		}

		$holidayCountedArray = array();

		foreach ($dateArgs as $holidayDate) {
			if (is_string($holidayDate = self::_getDateValue($holidayDate))) {
				return self::$_errorCodes['value'];
			}

			if (($startDate <= $holidayDate) && ($holidayDate <= $endDate)) {
				if ((self::DAYOFWEEK($holidayDate, 2) < 6) && !in_array($holidayDate, $holidayCountedArray)) {
					--$partWeekDays;
					$holidayCountedArray[] = $holidayDate;
				}
			}
		}

		if ($eDate < $sDate) {
			return 0 - ($wholeWeekDays + $partWeekDays);
		}

		return $wholeWeekDays + $partWeekDays;
	}

	static public function WORKDAY($startDate, $endDays)
	{
		$startDate = self::flattenSingleValue($startDate);
		$endDays = (int) self::flattenSingleValue($endDays);
		$dateArgs = self::flattenArray(func_get_args());
		array_shift($dateArgs);
		array_shift($dateArgs);
		if (is_string($startDate = self::_getDateValue($startDate)) || !is_numeric($endDays)) {
			return self::$_errorCodes['value'];
		}

		$startDate = (double) floor($startDate);

		if ($endDays == 0) {
			return $startDate;
		}

		$decrementing = ($endDays < 0 ? true : false);
		$startDoW = self::DAYOFWEEK($startDate, 3);

		if (5 <= self::DAYOFWEEK($startDate, 3)) {
			$startDate += ($decrementing ? (0 - $startDoW) + 4 : 7 - $startDoW);
			$decrementing ? $endDays++ : $endDays--;
		}

		$endDate = (double) $startDate + (intval($endDays / 5) * 7) + ($endDays % 5);
		$endDoW = self::DAYOFWEEK($endDate, 3);

		if (5 <= $endDoW) {
			$endDate += ($decrementing ? (0 - $endDoW) + 4 : 7 - $endDoW);
		}

		if (0 < count($dateArgs)) {
			$holidayCountedArray = $holidayDates = array();

			foreach ($dateArgs as $holidayDate) {
				if (!is_null($holidayDate) && ('' < trim($holidayDate))) {
					if (is_string($holidayDate = self::_getDateValue($holidayDate))) {
						return self::$_errorCodes['value'];
					}

					if (self::DAYOFWEEK($holidayDate, 3) < 5) {
						$holidayDates[] = $holidayDate;
					}
				}
			}

			if ($decrementing) {
				rsort($holidayDates, SORT_NUMERIC);
			}
			else {
				sort($holidayDates, SORT_NUMERIC);
			}

			foreach ($holidayDates as $holidayDate) {
				if ($decrementing) {
					if (($holidayDate <= $startDate) && ($endDate <= $holidayDate)) {
						if (!in_array($holidayDate, $holidayCountedArray)) {
							--$endDate;
							$holidayCountedArray[] = $holidayDate;
						}
					}
				}
				else {
					if (($startDate <= $holidayDate) && ($holidayDate <= $endDate)) {
						if (!in_array($holidayDate, $holidayCountedArray)) {
							++$endDate;
							$holidayCountedArray[] = $holidayDate;
						}
					}
				}

				$endDoW = self::DAYOFWEEK($endDate, 3);

				if (5 <= $endDoW) {
					$endDate += ($decrementing ? (0 - $endDoW) + 4 : 7 - $endDoW);
				}
			}
		}

		switch (self::getReturnDateType()) {
		case self::RETURNDATE_EXCEL:
			return (double) $endDate;
			break;

		case self::RETURNDATE_PHP_NUMERIC:
			return (int) PHPExcel_Shared_Date::ExcelToPHP($endDate);
			break;

		case self::RETURNDATE_PHP_OBJECT:
			return PHPExcel_Shared_Date::ExcelToPHPObject($endDate);
			break;
		}
	}

	static public function DAYOFMONTH($dateValue = 1)
	{
		$dateValue = self::flattenSingleValue($dateValue);

		if (is_string($dateValue = self::_getDateValue($dateValue))) {
			return self::$_errorCodes['value'];
		}
		else if ($dateValue == 0) {
			return 0;
		}
		else if ($dateValue < 0) {
			return self::$_errorCodes['num'];
		}

		$PHPDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($dateValue);
		return (int) $PHPDateObject->format('j');
	}

	static public function DAYOFWEEK($dateValue = 1, $style = 1)
	{
		$dateValue = self::flattenSingleValue($dateValue);
		$style = floor(self::flattenSingleValue($style));

		if (is_string($dateValue = self::_getDateValue($dateValue))) {
			return self::$_errorCodes['value'];
		}
		else if ($dateValue < 0) {
			return self::$_errorCodes['num'];
		}

		$PHPDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($dateValue);
		$DoW = $PHPDateObject->format('w');
		$firstDay = 1;

		switch ($style) {
		case 1:
			++$DoW;
			break;

		case 2:
			if ($DoW == 0) {
				$DoW = 7;
			}

			break;

		case 3:
			if ($DoW == 0) {
				$DoW = 7;
			}

			$firstDay = 0;
			--$DoW;
			break;

		default:
		}

		if (self::$compatibilityMode == self::COMPATIBILITY_EXCEL) {
			if (($PHPDateObject->format('Y') == 1900) && ($PHPDateObject->format('n') <= 2)) {
				--$DoW;

				if ($DoW < $firstDay) {
					$DoW += 7;
				}
			}
		}

		return (int) $DoW;
	}

	static public function WEEKOFYEAR($dateValue = 1, $method = 1)
	{
		$dateValue = self::flattenSingleValue($dateValue);
		$method = floor(self::flattenSingleValue($method));

		if (!is_numeric($method)) {
			return self::$_errorCodes['value'];
		}
		else {
			if (($method < 1) || (2 < $method)) {
				return self::$_errorCodes['num'];
			}
		}

		if (is_string($dateValue = self::_getDateValue($dateValue))) {
			return self::$_errorCodes['value'];
		}
		else if ($dateValue < 0) {
			return self::$_errorCodes['num'];
		}

		$PHPDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($dateValue);
		$dayOfYear = $PHPDateObject->format('z');
		$dow = $PHPDateObject->format('w');
		$PHPDateObject->modify('-' . $dayOfYear . ' days');
		$dow = $PHPDateObject->format('w');
		$daysInFirstWeek = 7 - (($dow + (2 - $method)) % 7);
		$dayOfYear -= $daysInFirstWeek;
		$weekOfYear = ceil($dayOfYear / 7) + 1;
		return (int) $weekOfYear;
	}

	static public function MONTHOFYEAR($dateValue = 1)
	{
		$dateValue = self::flattenSingleValue($dateValue);

		if (is_string($dateValue = self::_getDateValue($dateValue))) {
			return self::$_errorCodes['value'];
		}
		else if ($dateValue < 0) {
			return self::$_errorCodes['num'];
		}

		$PHPDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($dateValue);
		return (int) $PHPDateObject->format('n');
	}

	static public function YEAR($dateValue = 1)
	{
		$dateValue = self::flattenSingleValue($dateValue);

		if (is_string($dateValue = self::_getDateValue($dateValue))) {
			return self::$_errorCodes['value'];
		}
		else if ($dateValue < 0) {
			return self::$_errorCodes['num'];
		}

		$PHPDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($dateValue);
		return (int) $PHPDateObject->format('Y');
	}

	static public function HOUROFDAY($timeValue = 0)
	{
		$timeValue = self::flattenSingleValue($timeValue);

		if (!is_numeric($timeValue)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
				$testVal = strtok($timeValue, '/-: ');

				if (strlen($testVal) < strlen($timeValue)) {
					return self::$_errorCodes['value'];
				}
			}

			$timeValue = self::_getTimeValue($timeValue);

			if (is_string($timeValue)) {
				return self::$_errorCodes['value'];
			}
		}

		if (1 <= $timeValue) {
			$timeValue = fmod($timeValue, 1);
		}
		else if ($timeValue < 0) {
			return self::$_errorCodes['num'];
		}

		$timeValue = PHPExcel_Shared_Date::ExcelToPHP($timeValue);
		return (int) gmdate('G', $timeValue);
	}

	static public function MINUTEOFHOUR($timeValue = 0)
	{
		$timeValue = $timeTester = self::flattenSingleValue($timeValue);

		if (!is_numeric($timeValue)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
				$testVal = strtok($timeValue, '/-: ');

				if (strlen($testVal) < strlen($timeValue)) {
					return self::$_errorCodes['value'];
				}
			}

			$timeValue = self::_getTimeValue($timeValue);

			if (is_string($timeValue)) {
				return self::$_errorCodes['value'];
			}
		}

		if (1 <= $timeValue) {
			$timeValue = fmod($timeValue, 1);
		}
		else if ($timeValue < 0) {
			return self::$_errorCodes['num'];
		}

		$timeValue = PHPExcel_Shared_Date::ExcelToPHP($timeValue);
		return (int) gmdate('i', $timeValue);
	}

	static public function SECONDOFMINUTE($timeValue = 0)
	{
		$timeValue = self::flattenSingleValue($timeValue);

		if (!is_numeric($timeValue)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
				$testVal = strtok($timeValue, '/-: ');

				if (strlen($testVal) < strlen($timeValue)) {
					return self::$_errorCodes['value'];
				}
			}

			$timeValue = self::_getTimeValue($timeValue);

			if (is_string($timeValue)) {
				return self::$_errorCodes['value'];
			}
		}

		if (1 <= $timeValue) {
			$timeValue = fmod($timeValue, 1);
		}
		else if ($timeValue < 0) {
			return self::$_errorCodes['num'];
		}

		$timeValue = PHPExcel_Shared_Date::ExcelToPHP($timeValue);
		return (int) gmdate('s', $timeValue);
	}

	static private function _adjustDateByMonths($dateValue = 0, $adjustmentMonths = 0)
	{
		$PHPDateObject = PHPExcel_Shared_Date::ExcelToPHPObject($dateValue);
		$oMonth = (int) $PHPDateObject->format('m');
		$oYear = (int) $PHPDateObject->format('Y');
		$adjustmentMonthsString = (string) $adjustmentMonths;

		if (0 < $adjustmentMonths) {
			$adjustmentMonthsString = '+' . $adjustmentMonths;
		}

		if ($adjustmentMonths != 0) {
			$PHPDateObject->modify($adjustmentMonthsString . ' months');
		}

		$nMonth = (int) $PHPDateObject->format('m');
		$nYear = (int) $PHPDateObject->format('Y');
		$monthDiff = ($nMonth - $oMonth) + (($nYear - $oYear) * 12);

		if ($monthDiff != $adjustmentMonths) {
			$adjustDays = (int) $PHPDateObject->format('d');
			$adjustDaysString = '-' . $adjustDays . ' days';
			$PHPDateObject->modify($adjustDaysString);
		}

		return $PHPDateObject;
	}

	static public function EDATE($dateValue = 1, $adjustmentMonths = 0)
	{
		$dateValue = self::flattenSingleValue($dateValue);
		$adjustmentMonths = floor(self::flattenSingleValue($adjustmentMonths));

		if (!is_numeric($adjustmentMonths)) {
			return self::$_errorCodes['value'];
		}

		if (is_string($dateValue = self::_getDateValue($dateValue))) {
			return self::$_errorCodes['value'];
		}

		$PHPDateObject = self::_adjustDateByMonths($dateValue, $adjustmentMonths);

		switch (self::getReturnDateType()) {
		case self::RETURNDATE_EXCEL:
			return (double) PHPExcel_Shared_Date::PHPToExcel($PHPDateObject);
			break;

		case self::RETURNDATE_PHP_NUMERIC:
			return (int) PHPExcel_Shared_Date::ExcelToPHP(PHPExcel_Shared_Date::PHPToExcel($PHPDateObject));
			break;

		case self::RETURNDATE_PHP_OBJECT:
			return $PHPDateObject;
			break;
		}
	}

	static public function EOMONTH($dateValue = 1, $adjustmentMonths = 0)
	{
		$dateValue = self::flattenSingleValue($dateValue);
		$adjustmentMonths = floor(self::flattenSingleValue($adjustmentMonths));

		if (!is_numeric($adjustmentMonths)) {
			return self::$_errorCodes['value'];
		}

		if (is_string($dateValue = self::_getDateValue($dateValue))) {
			return self::$_errorCodes['value'];
		}

		$PHPDateObject = self::_adjustDateByMonths($dateValue, $adjustmentMonths + 1);
		$adjustDays = (int) $PHPDateObject->format('d');
		$adjustDaysString = '-' . $adjustDays . ' days';
		$PHPDateObject->modify($adjustDaysString);

		switch (self::getReturnDateType()) {
		case self::RETURNDATE_EXCEL:
			return (double) PHPExcel_Shared_Date::PHPToExcel($PHPDateObject);
			break;

		case self::RETURNDATE_PHP_NUMERIC:
			return (int) PHPExcel_Shared_Date::ExcelToPHP(PHPExcel_Shared_Date::PHPToExcel($PHPDateObject));
			break;

		case self::RETURNDATE_PHP_OBJECT:
			return $PHPDateObject;
			break;
		}
	}

	static public function TRUNC($value = 0, $number_digits = 0)
	{
		$value = self::flattenSingleValue($value);
		$number_digits = self::flattenSingleValue($number_digits);

		if ($number_digits < 0) {
			return self::$_errorCodes['value'];
		}

		if (0 < $number_digits) {
			$value = $value * pow(10, $number_digits);
		}

		$value = intval($value);

		if (0 < $number_digits) {
			$value = $value / pow(10, $number_digits);
		}

		return $value;
	}

	static public function POWER($x = 0, $y = 2)
	{
		$x = self::flattenSingleValue($x);
		$y = self::flattenSingleValue($y);
		if (($x == 0) && ($y <= 0)) {
			return self::$_errorCodes['divisionbyzero'];
		}

		return pow($x, $y);
	}

	static private function _nbrConversionFormat($xVal, $places)
	{
		if (!is_null($places)) {
			if (strlen($xVal) <= $places) {
				return substr(str_pad($xVal, $places, '0', STR_PAD_LEFT), -10);
			}
			else {
				return self::$_errorCodes['num'];
			}
		}

		return substr($xVal, -10);
	}

	static public function BINTODEC($x)
	{
		$x = self::flattenSingleValue($x);

		if (is_bool($x)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				$x = (int) $x;
			}
			else {
				return self::$_errorCodes['value'];
			}
		}

		if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
			$x = floor($x);
		}

		$x = (string) $x;

		if (preg_match_all('/[01]/', $x, $out) < strlen($x)) {
			return self::$_errorCodes['num'];
		}

		if (10 < strlen($x)) {
			return self::$_errorCodes['num'];
		}
		else if (strlen($x) == 10) {
			$x = substr($x, -9);
			return '-' . (512 - bindec($x));
		}

		return bindec($x);
	}

	static public function BINTOHEX($x, $places = NULL)
	{
		$x = floor(self::flattenSingleValue($x));
		$places = self::flattenSingleValue($places);

		if (is_bool($x)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				$x = (int) $x;
			}
			else {
				return self::$_errorCodes['value'];
			}
		}

		if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
			$x = floor($x);
		}

		$x = (string) $x;

		if (preg_match_all('/[01]/', $x, $out) < strlen($x)) {
			return self::$_errorCodes['num'];
		}

		if (10 < strlen($x)) {
			return self::$_errorCodes['num'];
		}
		else if (strlen($x) == 10) {
			return str_repeat('F', 8) . substr(strtoupper(dechex(bindec(substr($x, -9)))), -2);
		}

		$hexVal = (string) strtoupper(dechex(bindec($x)));
		return self::_nbrConversionFormat($hexVal, $places);
	}

	static public function BINTOOCT($x, $places = NULL)
	{
		$x = floor(self::flattenSingleValue($x));
		$places = self::flattenSingleValue($places);

		if (is_bool($x)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				$x = (int) $x;
			}
			else {
				return self::$_errorCodes['value'];
			}
		}

		if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
			$x = floor($x);
		}

		$x = (string) $x;

		if (preg_match_all('/[01]/', $x, $out) < strlen($x)) {
			return self::$_errorCodes['num'];
		}

		if (10 < strlen($x)) {
			return self::$_errorCodes['num'];
		}
		else if (strlen($x) == 10) {
			return str_repeat('7', 7) . substr(strtoupper(decoct(bindec(substr($x, -9)))), -3);
		}

		$octVal = (string) decoct(bindec($x));
		return self::_nbrConversionFormat($octVal, $places);
	}

	static public function DECTOBIN($x, $places = NULL)
	{
		$x = self::flattenSingleValue($x);
		$places = self::flattenSingleValue($places);

		if (is_bool($x)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				$x = (int) $x;
			}
			else {
				return self::$_errorCodes['value'];
			}
		}

		$x = (string) $x;

		if (preg_match_all('/[-0123456789.]/', $x, $out) < strlen($x)) {
			return self::$_errorCodes['value'];
		}

		$x = (string) floor($x);
		$r = decbin($x);

		if (strlen($r) == 32) {
			$r = substr($r, -10);
		}
		else if (11 < strlen($r)) {
			return self::$_errorCodes['num'];
		}

		return self::_nbrConversionFormat($r, $places);
	}

	static public function DECTOOCT($x, $places = NULL)
	{
		$x = self::flattenSingleValue($x);
		$places = self::flattenSingleValue($places);

		if (is_bool($x)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				$x = (int) $x;
			}
			else {
				return self::$_errorCodes['value'];
			}
		}

		$x = (string) $x;

		if (preg_match_all('/[-0123456789.]/', $x, $out) < strlen($x)) {
			return self::$_errorCodes['value'];
		}

		$x = (string) floor($x);
		$r = decoct($x);

		if (strlen($r) == 11) {
			$r = substr($r, -10);
		}

		return self::_nbrConversionFormat($r, $places);
	}

	static public function DECTOHEX($x, $places = NULL)
	{
		$x = self::flattenSingleValue($x);
		$places = self::flattenSingleValue($places);

		if (is_bool($x)) {
			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				$x = (int) $x;
			}
			else {
				return self::$_errorCodes['value'];
			}
		}

		$x = (string) $x;

		if (preg_match_all('/[-0123456789.]/', $x, $out) < strlen($x)) {
			return self::$_errorCodes['value'];
		}

		$x = (string) floor($x);
		$r = strtoupper(dechex($x));

		if (strlen($r) == 8) {
			$r = 'FF' . $r;
		}

		return self::_nbrConversionFormat($r, $places);
	}

	static public function HEXTOBIN($x, $places = NULL)
	{
		$x = self::flattenSingleValue($x);
		$places = self::flattenSingleValue($places);

		if (is_bool($x)) {
			return self::$_errorCodes['value'];
		}

		$x = (string) $x;

		if (preg_match_all('/[0123456789ABCDEF]/', strtoupper($x), $out) < strlen($x)) {
			return self::$_errorCodes['num'];
		}

		$binVal = decbin(hexdec($x));
		return substr(self::_nbrConversionFormat($binVal, $places), -10);
	}

	static public function HEXTOOCT($x, $places = NULL)
	{
		$x = self::flattenSingleValue($x);
		$places = self::flattenSingleValue($places);

		if (is_bool($x)) {
			return self::$_errorCodes['value'];
		}

		$x = (string) $x;

		if (preg_match_all('/[0123456789ABCDEF]/', strtoupper($x), $out) < strlen($x)) {
			return self::$_errorCodes['num'];
		}

		$octVal = decoct(hexdec($x));
		return self::_nbrConversionFormat($octVal, $places);
	}

	static public function HEXTODEC($x)
	{
		$x = self::flattenSingleValue($x);

		if (is_bool($x)) {
			return self::$_errorCodes['value'];
		}

		$x = (string) $x;

		if (preg_match_all('/[0123456789ABCDEF]/', strtoupper($x), $out) < strlen($x)) {
			return self::$_errorCodes['num'];
		}

		return hexdec($x);
	}

	static public function OCTTOBIN($x, $places = NULL)
	{
		$x = self::flattenSingleValue($x);
		$places = self::flattenSingleValue($places);

		if (is_bool($x)) {
			return self::$_errorCodes['value'];
		}

		$x = (string) $x;

		if (preg_match_all('/[01234567]/', $x, $out) != strlen($x)) {
			return self::$_errorCodes['num'];
		}

		$r = decbin(octdec($x));
		return self::_nbrConversionFormat($r, $places);
	}

	static public function OCTTODEC($x)
	{
		$x = self::flattenSingleValue($x);

		if (is_bool($x)) {
			return self::$_errorCodes['value'];
		}

		$x = (string) $x;

		if (preg_match_all('/[01234567]/', $x, $out) != strlen($x)) {
			return self::$_errorCodes['num'];
		}

		return octdec($x);
	}

	static public function OCTTOHEX($x, $places = NULL)
	{
		$x = self::flattenSingleValue($x);
		$places = self::flattenSingleValue($places);

		if (is_bool($x)) {
			return self::$_errorCodes['value'];
		}

		$x = (string) $x;

		if (preg_match_all('/[01234567]/', $x, $out) != strlen($x)) {
			return self::$_errorCodes['num'];
		}

		$hexVal = strtoupper(dechex(octdec($x)));
		return self::_nbrConversionFormat($hexVal, $places);
	}

	static public function _parseComplex($complexNumber)
	{
		$workString = (string) $complexNumber;
		$realNumber = $imaginary = 0;
		$suffix = substr($workString, -1);

		if (!is_numeric($suffix)) {
			$workString = substr($workString, 0, -1);
		}
		else {
			$suffix = '';
		}

		$leadingSign = 0;

		if (0 < strlen($workString)) {
			$leadingSign = (($workString[0] == '+') || ($workString[0] == '-') ? 1 : 0);
		}

		$power = '';
		$realNumber = strtok($workString, '+-');

		if (strtoupper(substr($realNumber, -1)) == 'E') {
			$power = strtok('+-');
			++$leadingSign;
		}

		$realNumber = substr($workString, 0, strlen($realNumber) + strlen($power) + $leadingSign);

		if ($suffix != '') {
			$imaginary = substr($workString, strlen($realNumber));
			if (($imaginary == '') && (($realNumber == '') || ($realNumber == '+') || ($realNumber == '-'))) {
				$imaginary = $realNumber . '1';
				$realNumber = '0';
			}
			else if ($imaginary == '') {
				$imaginary = $realNumber;
				$realNumber = '0';
			}
			else {
				if (($imaginary == '+') || ($imaginary == '-')) {
					$imaginary .= '1';
				}
			}
		}

		$complexArray = array('real' => $realNumber, 'imaginary' => $imaginary, 'suffix' => $suffix);
		return $complexArray;
	}

	static private function _cleanComplex($complexNumber)
	{
		if ($complexNumber[0] == '+') {
			$complexNumber = substr($complexNumber, 1);
		}

		if ($complexNumber[0] == '0') {
			$complexNumber = substr($complexNumber, 1);
		}

		if ($complexNumber[0] == '.') {
			$complexNumber = '0' . $complexNumber;
		}

		if ($complexNumber[0] == '+') {
			$complexNumber = substr($complexNumber, 1);
		}

		return $complexNumber;
	}

	static public function COMPLEX($realNumber = 0, $imaginary = 0, $suffix = 'i')
	{
		$realNumber = (is_null($realNumber) ? 0 : (double) self::flattenSingleValue($realNumber));
		$imaginary = (is_null($imaginary) ? 0 : (double) self::flattenSingleValue($imaginary));
		$suffix = (is_null($suffix) ? 'i' : self::flattenSingleValue($suffix));
		if (is_numeric($realNumber) && is_numeric($imaginary) && (($suffix == 'i') || ($suffix == 'j') || ($suffix == ''))) {
			if ($suffix == '') {
				$suffix = 'i';
			}

			if ($realNumber == 0) {
				if ($imaginary == 0) {
					return (string) '0';
				}
				else if ($imaginary == 1) {
					return (string) $suffix;
				}
				else if ($imaginary == -1) {
					return (string) '-' . $suffix;
				}

				return (string) $imaginary . $suffix;
			}
			else if ($imaginary == 0) {
				return (string) $realNumber;
			}
			else if ($imaginary == 1) {
				return (string) $realNumber . '+' . $suffix;
			}
			else if ($imaginary == -1) {
				return (string) $realNumber . '-' . $suffix;
			}

			if (0 < $imaginary) {
				$imaginary = (string) '+' . $imaginary;
			}

			return (string) $realNumber . $imaginary . $suffix;
		}

		return self::$_errorCodes['value'];
	}

	static public function IMAGINARY($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		return $parsedComplex['imaginary'];
	}

	static public function IMREAL($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		return $parsedComplex['real'];
	}

	static public function IMABS($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		return sqrt(($parsedComplex['real'] * $parsedComplex['real']) + ($parsedComplex['imaginary'] * $parsedComplex['imaginary']));
	}

	static public function IMARGUMENT($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		if ($parsedComplex['real'] == 0) {
			if ($parsedComplex['imaginary'] == 0) {
				return 0;
			}
			else if ($parsedComplex['imaginary'] < 0) {
				return M_PI / -2;
			}
			else {
				return M_PI / 2;
			}
		}
		else if (0 < $parsedComplex['real']) {
			return atan($parsedComplex['imaginary'] / $parsedComplex['real']);
		}
		else if ($parsedComplex['imaginary'] < 0) {
			return 0 - M_PI - atan(abs($parsedComplex['imaginary']) / abs($parsedComplex['real']));
		}
		else {
			return M_PI - atan($parsedComplex['imaginary'] / abs($parsedComplex['real']));
		}
	}

	static public function IMCONJUGATE($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		if ($parsedComplex['imaginary'] == 0) {
			return $parsedComplex['real'];
		}
		else {
			return self::_cleanComplex(self::COMPLEX($parsedComplex['real'], 0 - $parsedComplex['imaginary'], $parsedComplex['suffix']));
		}
	}

	static public function IMCOS($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		if ($parsedComplex['imaginary'] == 0) {
			return cos($parsedComplex['real']);
		}
		else {
			return self::IMCONJUGATE(self::COMPLEX(cos($parsedComplex['real']) * cosh($parsedComplex['imaginary']), sin($parsedComplex['real']) * sinh($parsedComplex['imaginary']), $parsedComplex['suffix']));
		}
	}

	static public function IMSIN($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		if ($parsedComplex['imaginary'] == 0) {
			return sin($parsedComplex['real']);
		}
		else {
			return self::COMPLEX(sin($parsedComplex['real']) * cosh($parsedComplex['imaginary']), cos($parsedComplex['real']) * sinh($parsedComplex['imaginary']), $parsedComplex['suffix']);
		}
	}

	static public function IMSQRT($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		$theta = self::IMARGUMENT($complexNumber);
		$d1 = cos($theta / 2);
		$d2 = sin($theta / 2);
		$r = sqrt(sqrt(($parsedComplex['real'] * $parsedComplex['real']) + ($parsedComplex['imaginary'] * $parsedComplex['imaginary'])));

		if ($parsedComplex['suffix'] == '') {
			return self::COMPLEX($d1 * $r, $d2 * $r);
		}
		else {
			return self::COMPLEX($d1 * $r, $d2 * $r, $parsedComplex['suffix']);
		}
	}

	static public function IMLN($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		if (($parsedComplex['real'] == 0) && ($parsedComplex['imaginary'] == 0)) {
			return self::$_errorCodes['num'];
		}

		$logR = log(sqrt(($parsedComplex['real'] * $parsedComplex['real']) + ($parsedComplex['imaginary'] * $parsedComplex['imaginary'])));
		$t = self::IMARGUMENT($complexNumber);

		if ($parsedComplex['suffix'] == '') {
			return self::COMPLEX($logR, $t);
		}
		else {
			return self::COMPLEX($logR, $t, $parsedComplex['suffix']);
		}
	}

	static public function IMLOG10($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		if (($parsedComplex['real'] == 0) && ($parsedComplex['imaginary'] == 0)) {
			return self::$_errorCodes['num'];
		}
		else {
			if ((0 < $parsedComplex['real']) && ($parsedComplex['imaginary'] == 0)) {
				return log10($parsedComplex['real']);
			}
		}

		return self::IMPRODUCT(log10(EULER), self::IMLN($complexNumber));
	}

	static public function IMLOG2($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		if (($parsedComplex['real'] == 0) && ($parsedComplex['imaginary'] == 0)) {
			return self::$_errorCodes['num'];
		}
		else {
			if ((0 < $parsedComplex['real']) && ($parsedComplex['imaginary'] == 0)) {
				return log($parsedComplex['real'], 2);
			}
		}

		return self::IMPRODUCT(log(EULER, 2), self::IMLN($complexNumber));
	}

	static public function IMEXP($complexNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		if (($parsedComplex['real'] == 0) && ($parsedComplex['imaginary'] == 0)) {
			return '1';
		}

		$e = exp($parsedComplex['real']);
		$eX = $e * cos($parsedComplex['imaginary']);
		$eY = $e * sin($parsedComplex['imaginary']);

		if ($parsedComplex['suffix'] == '') {
			return self::COMPLEX($eX, $eY);
		}
		else {
			return self::COMPLEX($eX, $eY, $parsedComplex['suffix']);
		}
	}

	static public function IMPOWER($complexNumber, $realNumber)
	{
		$complexNumber = self::flattenSingleValue($complexNumber);
		$realNumber = self::flattenSingleValue($realNumber);

		if (!is_numeric($realNumber)) {
			return self::$_errorCodes['value'];
		}

		$parsedComplex = self::_parseComplex($complexNumber);

		if (!is_array($parsedComplex)) {
			return $parsedComplex;
		}

		$r = sqrt(($parsedComplex['real'] * $parsedComplex['real']) + ($parsedComplex['imaginary'] * $parsedComplex['imaginary']));
		$rPower = pow($r, $realNumber);
		$theta = self::IMARGUMENT($complexNumber) * $realNumber;

		if ($theta == 0) {
			return 1;
		}
		else if ($parsedComplex['imaginary'] == 0) {
			return self::COMPLEX($rPower * cos($theta), $rPower * sin($theta), $parsedComplex['suffix']);
		}
		else {
			return self::COMPLEX($rPower * cos($theta), $rPower * sin($theta), $parsedComplex['suffix']);
		}
	}

	static public function IMDIV($complexDividend, $complexDivisor)
	{
		$complexDividend = self::flattenSingleValue($complexDividend);
		$complexDivisor = self::flattenSingleValue($complexDivisor);
		$parsedComplexDividend = self::_parseComplex($complexDividend);

		if (!is_array($parsedComplexDividend)) {
			return $parsedComplexDividend;
		}

		$parsedComplexDivisor = self::_parseComplex($complexDivisor);

		if (!is_array($parsedComplexDivisor)) {
			return $parsedComplexDividend;
		}

		if (($parsedComplexDividend['suffix'] != '') && ($parsedComplexDivisor['suffix'] != '') && ($parsedComplexDividend['suffix'] != $parsedComplexDivisor['suffix'])) {
			return self::$_errorCodes['num'];
		}

		if (($parsedComplexDividend['suffix'] != '') && ($parsedComplexDivisor['suffix'] == '')) {
			$parsedComplexDivisor['suffix'] = $parsedComplexDividend['suffix'];
		}

		$d1 = ($parsedComplexDividend['real'] * $parsedComplexDivisor['real']) + ($parsedComplexDividend['imaginary'] * $parsedComplexDivisor['imaginary']);
		$d2 = ($parsedComplexDividend['imaginary'] * $parsedComplexDivisor['real']) - ($parsedComplexDividend['real'] * $parsedComplexDivisor['imaginary']);
		$d3 = ($parsedComplexDivisor['real'] * $parsedComplexDivisor['real']) + ($parsedComplexDivisor['imaginary'] * $parsedComplexDivisor['imaginary']);
		$r = $d1 / $d3;
		$i = $d2 / $d3;

		if (0 < $i) {
			return self::_cleanComplex($r . '+' . $i . $parsedComplexDivisor['suffix']);
		}
		else if ($i < 0) {
			return self::_cleanComplex($r . $i . $parsedComplexDivisor['suffix']);
		}
		else {
			return $r;
		}
	}

	static public function IMSUB($complexNumber1, $complexNumber2)
	{
		$complexNumber1 = self::flattenSingleValue($complexNumber1);
		$complexNumber2 = self::flattenSingleValue($complexNumber2);
		$parsedComplex1 = self::_parseComplex($complexNumber1);

		if (!is_array($parsedComplex1)) {
			return $parsedComplex1;
		}

		$parsedComplex2 = self::_parseComplex($complexNumber2);

		if (!is_array($parsedComplex2)) {
			return $parsedComplex2;
		}

		if (($parsedComplex1['suffix'] != '') && ($parsedComplex2['suffix'] != '') && ($parsedComplex1['suffix'] != $parsedComplex2['suffix'])) {
			return self::$_errorCodes['num'];
		}
		else {
			if (($parsedComplex1['suffix'] == '') && ($parsedComplex2['suffix'] != '')) {
				$parsedComplex1['suffix'] = $parsedComplex2['suffix'];
			}
		}

		$d1 = $parsedComplex1['real'] - $parsedComplex2['real'];
		$d2 = $parsedComplex1['imaginary'] - $parsedComplex2['imaginary'];
		return self::COMPLEX($d1, $d2, $parsedComplex1['suffix']);
	}

	static public function IMSUM()
	{
		$returnValue = self::_parseComplex('0');
		$activeSuffix = '';
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			$parsedComplex = self::_parseComplex($arg);

			if (!is_array($parsedComplex)) {
				return $parsedComplex;
			}

			if ($activeSuffix == '') {
				$activeSuffix = $parsedComplex['suffix'];
			}
			else {
				if (($parsedComplex['suffix'] != '') && ($activeSuffix != $parsedComplex['suffix'])) {
					return self::$_errorCodes['value'];
				}
			}

			$returnValue['real'] += $parsedComplex['real'];
			$returnValue['imaginary'] += $parsedComplex['imaginary'];
		}

		if ($returnValue['imaginary'] == 0) {
			$activeSuffix = '';
		}

		return self::COMPLEX($returnValue['real'], $returnValue['imaginary'], $activeSuffix);
	}

	static public function IMPRODUCT()
	{
		$returnValue = self::_parseComplex('1');
		$activeSuffix = '';
		$aArgs = self::flattenArray(func_get_args());

		foreach ($aArgs as $arg) {
			$parsedComplex = self::_parseComplex($arg);

			if (!is_array($parsedComplex)) {
				return $parsedComplex;
			}

			$workValue = $returnValue;
			if (($parsedComplex['suffix'] != '') && ($activeSuffix == '')) {
				$activeSuffix = $parsedComplex['suffix'];
			}
			else {
				if (($parsedComplex['suffix'] != '') && ($activeSuffix != $parsedComplex['suffix'])) {
					return self::$_errorCodes['num'];
				}
			}

			$returnValue['real'] = ($workValue['real'] * $parsedComplex['real']) - ($workValue['imaginary'] * $parsedComplex['imaginary']);
			$returnValue['imaginary'] = ($workValue['real'] * $parsedComplex['imaginary']) + ($workValue['imaginary'] * $parsedComplex['real']);
		}

		if ($returnValue['imaginary'] == 0) {
			$activeSuffix = '';
		}

		return self::COMPLEX($returnValue['real'], $returnValue['imaginary'], $activeSuffix);
	}

	static public function getConversionGroups()
	{
		$conversionGroups = array();

		foreach (self::$_conversionUnits as $conversionUnit) {
			$conversionGroups[] = $conversionUnit['Group'];
		}

		return array_merge(array_unique($conversionGroups));
	}

	static public function getConversionGroupUnits($group = NULL)
	{
		$conversionGroups = array();

		foreach (self::$_conversionUnits as $conversionUnit => $conversionGroup) {
			if (is_null($group) || ($conversionGroup['Group'] == $group)) {
				$conversionGroups[$conversionGroup['Group']][] = $conversionUnit;
			}
		}

		return $conversionGroups;
	}

	static public function getConversionGroupUnitDetails($group = NULL)
	{
		$conversionGroups = array();

		foreach (self::$_conversionUnits as $conversionUnit => $conversionGroup) {
			if (is_null($group) || ($conversionGroup['Group'] == $group)) {
				$conversionGroups[$conversionGroup['Group']][] = array('unit' => $conversionUnit, 'description' => $conversionGroup['Unit Name']);
			}
		}

		return $conversionGroups;
	}

	static public function getConversionMultipliers()
	{
		return self::$_conversionMultipliers;
	}

	static public function CONVERTUOM($value, $fromUOM, $toUOM)
	{
		$value = self::flattenSingleValue($value);
		$fromUOM = self::flattenSingleValue($fromUOM);
		$toUOM = self::flattenSingleValue($toUOM);

		if (!is_numeric($value)) {
			return self::$_errorCodes['value'];
		}

		$fromMultiplier = 1;

		if (isset(self::$_conversionUnits[$fromUOM])) {
			$unitGroup1 = self::$_conversionUnits[$fromUOM]['Group'];
		}
		else {
			$fromMultiplier = substr($fromUOM, 0, 1);
			$fromUOM = substr($fromUOM, 1);

			if (isset(self::$_conversionMultipliers[$fromMultiplier])) {
				$fromMultiplier = self::$_conversionMultipliers[$fromMultiplier]['multiplier'];
			}
			else {
				return self::$_errorCodes['na'];
			}

			if (isset(self::$_conversionUnits[$fromUOM]) && self::$_conversionUnits[$fromUOM]['AllowPrefix']) {
				$unitGroup1 = self::$_conversionUnits[$fromUOM]['Group'];
			}
			else {
				return self::$_errorCodes['na'];
			}
		}

		$value *= $fromMultiplier;
		$toMultiplier = 1;

		if (isset(self::$_conversionUnits[$toUOM])) {
			$unitGroup2 = self::$_conversionUnits[$toUOM]['Group'];
		}
		else {
			$toMultiplier = substr($toUOM, 0, 1);
			$toUOM = substr($toUOM, 1);

			if (isset(self::$_conversionMultipliers[$toMultiplier])) {
				$toMultiplier = self::$_conversionMultipliers[$toMultiplier]['multiplier'];
			}
			else {
				return self::$_errorCodes['na'];
			}

			if (isset(self::$_conversionUnits[$toUOM]) && self::$_conversionUnits[$toUOM]['AllowPrefix']) {
				$unitGroup2 = self::$_conversionUnits[$toUOM]['Group'];
			}
			else {
				return self::$_errorCodes['na'];
			}
		}

		if ($unitGroup1 != $unitGroup2) {
			return self::$_errorCodes['na'];
		}

		if ($fromUOM == $toUOM) {
			return 1;
		}
		else if ($unitGroup1 == 'Temperature') {
			if (($fromUOM == 'F') || ($fromUOM == 'fah')) {
				if (($toUOM == 'F') || ($toUOM == 'fah')) {
					return 1;
				}
				else {
					$value = ($value - 32) / 1.8;
					if (($toUOM == 'K') || ($toUOM == 'kel')) {
						$value += 273.14999999999998;
					}

					return $value;
				}
			}
			else {
				if ((($fromUOM == 'K') || ($fromUOM == 'kel')) && (($toUOM == 'K') || ($toUOM == 'kel'))) {
					return 1;
				}
				else {
					if ((($fromUOM == 'C') || ($fromUOM == 'cel')) && (($toUOM == 'C') || ($toUOM == 'cel'))) {
						return 1;
					}
				}
			}

			if (($toUOM == 'F') || ($toUOM == 'fah')) {
				if (($fromUOM == 'K') || ($fromUOM == 'kel')) {
					$value -= 273.14999999999998;
				}

				return ($value * 1.8) + 32;
			}

			if (($toUOM == 'C') || ($toUOM == 'cel')) {
				return $value - 273.14999999999998;
			}

			return $value + 273.14999999999998;
		}

		return ($value * self::$_unitConversions[$unitGroup1][$fromUOM][$toUOM]) / $toMultiplier;
	}

	static public function BESSELI($x, $n)
	{
		$x = (is_null($x) ? 0 : self::flattenSingleValue($x));
		$n = (is_null($n) ? 0 : self::flattenSingleValue($n));
		if (is_numeric($x) && is_numeric($n)) {
			$n = floor($n);

			if ($n < 0) {
				return self::$_errorCodes['num'];
			}

			$f_2_PI = 2 * M_PI;

			if (abs($x) <= 30) {
				$fTerm = pow($x / 2, $n) / self::FACT($n);
				$nK = 1;
				$fResult = $fTerm;
				$fSqrX = ($x * $x) / 4;

				do {
					$fTerm *= $fSqrX;
					$fTerm /= $nK * ($nK + $n);
					$fResult += $fTerm;
				} while ((1.0E-10 < abs($fTerm)) && (++$nK < 100));
			}
			else {
				$fXAbs = abs($x);
				$fResult = exp($fXAbs) / sqrt($f_2_PI * $fXAbs);
				if ($n && 1 && ($x < 0)) {
					$fResult = 0 - $fResult;
				}
			}

			return $fResult;
		}

		return self::$_errorCodes['value'];
	}

	static public function BESSELJ($x, $n)
	{
		$x = (is_null($x) ? 0 : self::flattenSingleValue($x));
		$n = (is_null($n) ? 0 : self::flattenSingleValue($n));
		if (is_numeric($x) && is_numeric($n)) {
			$n = floor($n);

			if ($n < 0) {
				return self::$_errorCodes['num'];
			}

			$f_PI_DIV_2 = M_PI / 2;
			$f_PI_DIV_4 = M_PI / 4;
			$fResult = 0;

			if (abs($x) <= 30) {
				$fTerm = pow($x / 2, $n) / self::FACT($n);
				$nK = 1;
				$fResult = $fTerm;
				$fSqrX = ($x * $x) / -4;

				do {
					$fTerm *= $fSqrX;
					$fTerm /= $nK * ($nK + $n);
					$fResult += $fTerm;
				} while ((1.0E-10 < abs($fTerm)) && (++$nK < 100));
			}
			else {
				$fXAbs = abs($x);
				$fResult = sqrt(M_2DIVPI / $fXAbs) * cos($fXAbs - ($n * $f_PI_DIV_2) - $f_PI_DIV_4);
				if ($n && 1 && ($x < 0)) {
					$fResult = 0 - $fResult;
				}
			}

			return $fResult;
		}

		return self::$_errorCodes['value'];
	}

	static private function _Besselk0($fNum)
	{
		if ($fNum <= 2) {
			$fNum2 = $fNum * 0.5;
			$y = $fNum2 * $fNum2;
			$fRet = ((0 - log($fNum2)) * self::BESSELI($fNum, 0)) + -0.57721566000000002 + ($y * (0.4227842 + ($y * (0.23069756 + ($y * (0.034885899999999997 + ($y * (0.0026269800000000001 + ($y * (0.0001075 + ($y * 7.4000000000000003E-6)))))))))));
		}
		else {
			$y = 2 / $fNum;
			$fRet = (exp(0 - $fNum) / sqrt($fNum)) * (1.2533141400000001 + ($y * (-0.078323580000000004 + ($y * (0.021895680000000001 + ($y * (-0.010624460000000001 + ($y * (0.0058787199999999996 + ($y * (-0.0025154000000000001 + ($y * 0.00053207999999999999))))))))))));
		}

		return $fRet;
	}

	static private function _Besselk1($fNum)
	{
		if ($fNum <= 2) {
			$fNum2 = $fNum * 0.5;
			$y = $fNum2 * $fNum2;
			$fRet = (log($fNum2) * self::BESSELI($fNum, 1)) + ((1 + ($y * (0.15443144 + ($y * (-0.67278579000000005 + ($y * (-0.18156897 + ($y * (-0.019194019999999999 + ($y * (-0.0011040399999999999 + ($y * -4.6860000000000002E-5)))))))))))) / $fNum);
		}
		else {
			$y = 2 / $fNum;
			$fRet = (exp(0 - $fNum) / sqrt($fNum)) * (1.2533141400000001 + ($y * (0.23498619000000001 + ($y * (-0.036556199999999997 + ($y * (0.015042679999999999 + ($y * (-0.0078035300000000004 + ($y * (0.0032561399999999998 + ($y * -0.00068245000000000003))))))))))));
		}

		return $fRet;
	}

	static public function BESSELK($x, $ord)
	{
		$x = (is_null($x) ? 0 : self::flattenSingleValue($x));
		$ord = (is_null($ord) ? 0 : self::flattenSingleValue($ord));
		if (is_numeric($x) && is_numeric($ord)) {
			if (($ord < 0) || ($x == 0)) {
				return self::$_errorCodes['num'];
			}

			switch (floor($ord)) {
			case 0:
				return self::_Besselk0($x);
				break;

			case 1:
				return self::_Besselk1($x);
				break;

			default:
				$fTox = 2 / $x;
				$fBkm = self::_Besselk0($x);
				$fBk = self::_Besselk1($x);

				for ($n = 1; $n < $ord; ++$n) {
					$fBkp = $fBkm + ($n * $fTox * $fBk);
					$fBkm = $fBk;
					$fBk = $fBkp;
				}
			}

			return $fBk;
		}

		return self::$_errorCodes['value'];
	}

	static private function _Bessely0($fNum)
	{
		if ($fNum < 8) {
			$y = $fNum * $fNum;
			$f1 = -2957821389 + ($y * (7062834065 + ($y * (-512359803.60000002 + ($y * (10879881.289999999 + ($y * (-86327.92757 + ($y * 228.46227329999999)))))))));
			$f2 = 40076544269 + ($y * (745249964.79999995 + ($y * (7189466.4380000001 + ($y * (47447.2647 + ($y * (226.10302440000001 + $y))))))));
			$fRet = ($f1 / $f2) + (M_2DIVPI * self::BESSELJ($fNum, 0) * log($fNum));
		}
		else {
			$z = 8 / $fNum;
			$y = $z * $z;
			$xx = $fNum - 0.78539816399999995;
			$f1 = 1 + ($y * (-0.001098628627 + ($y * (2.734510407E-5 + ($y * (-2.0733706389999998E-6 + ($y * 2.0938872110000001E-7)))))));
			$f2 = -0.015624999949999999 + ($y * (0.0001430488765 + ($y * (-6.9111476509999999E-6 + ($y * (7.6210951610000005E-7 + ($y * -9.3494515199999995E-8)))))));
			$fRet = sqrt(M_2DIVPI / $fNum) * ((sin($xx) * $f1) + ($z * cos($xx) * $f2));
		}

		return $fRet;
	}

	static private function _Bessely1($fNum)
	{
		if ($fNum < 8) {
			$y = $fNum * $fNum;
			$f1 = $fNum * (-4900604943000 + ($y * (1275274390000 + ($y * (-51534381390 + ($y * (734926455.10000002 + ($y * (-4237922.7259999998 + ($y * 8511.9379349999999))))))))));
			$f2 = 24995805700000 + ($y * (424441966400 + ($y * (3733650367 + ($y * (22459040.02 + ($y * (102042.605 + ($y * (354.96328849999998 + $y))))))))));
			$fRet = ($f1 / $f2) + (M_2DIVPI * ((self::BESSELJ($fNum, 1) * log($fNum)) - (1 / $fNum)));
		}
		else {
			$z = 8 / $fNum;
			$y = $z * $z;
			$xx = $fNum - 2.3561944910000001;
			$f1 = 1 + ($y * (0.0018310500000000001 + ($y * (-3.5163964960000002E-5 + ($y * (2.4575201739999999E-6 + ($y * -240337.019)))))));
			$f2 = 0.046874999950000003 + ($y * (-0.00020026908730000001 + ($y * (8.4491990959999996E-6 + ($y * (-8.8228987E-7 + ($y * 1.0578741200000001E-7)))))));
			$fRet = sqrt(M_2DIVPI / $fNum) * ((sin($xx) * $f1) + ($z * cos($xx) * $f2));
		}

		return $fRet;
	}

	static public function BESSELY($x, $ord)
	{
		$x = (is_null($x) ? 0 : self::flattenSingleValue($x));
		$ord = (is_null($ord) ? 0 : self::flattenSingleValue($ord));
		if (is_numeric($x) && is_numeric($ord)) {
			if (($ord < 0) || ($x == 0)) {
				return self::$_errorCodes['num'];
			}

			switch (floor($ord)) {
			case 0:
				return self::_Bessely0($x);
				break;

			case 1:
				return self::_Bessely1($x);
				break;

			default:
				$fTox = 2 / $x;
				$fBym = self::_Bessely0($x);
				$fBy = self::_Bessely1($x);

				for ($n = 1; $n < $ord; ++$n) {
					$fByp = ($n * $fTox * $fBy) - $fBym;
					$fBym = $fBy;
					$fBy = $fByp;
				}
			}

			return $fBy;
		}

		return self::$_errorCodes['value'];
	}

	static public function DELTA($a, $b = 0)
	{
		$a = self::flattenSingleValue($a);
		$b = self::flattenSingleValue($b);
		return (int) ($a == $b);
	}

	static public function GESTEP($number, $step = 0)
	{
		$number = self::flattenSingleValue($number);
		$step = self::flattenSingleValue($step);
		return (int) ($step <= $number);
	}

	static private function _erfVal($x)
	{
		if (2.2000000000000002 < abs($x)) {
			return 1 - self::_erfcVal($x);
		}

		$sum = $term = $x;
		$xsqr = $x * $x;
		$j = 1;

		do {
			$term *= $xsqr / $j;
			$sum -= $term / ((2 * $j) + 1);
			++$j;
			$term *= $xsqr / $j;
			$sum += $term / ((2 * $j) + 1);
			++$j;

			if ($sum == 0) {
				break;
			}
		} while (PRECISION < abs($term / $sum));

		return self::$_two_sqrtpi * $sum;
	}

	static public function ERF($lower, $upper = NULL)
	{
		$lower = self::flattenSingleValue($lower);
		$upper = self::flattenSingleValue($upper);

		if (is_numeric($lower)) {
			if ($lower < 0) {
				return self::$_errorCodes['num'];
			}

			if (is_null($upper)) {
				return self::_erfVal($lower);
			}

			if (is_numeric($upper)) {
				if ($upper < 0) {
					return self::$_errorCodes['num'];
				}

				return self::_erfVal($upper) - self::_erfVal($lower);
			}
		}

		return self::$_errorCodes['value'];
	}

	static private function _erfcVal($x)
	{
		if (abs($x) < 2.2000000000000002) {
			return 1 - self::_erfVal($x);
		}

		if ($x < 0) {
			return 2 - self::erfc(0 - $x);
		}

		$a = $n = 1;
		$b = $c = $x;
		$d = ($x * $x) + 0.5;
		$q1 = $q2 = $b / $d;
		$t = 0;

		do {
			$t = ($a * $n) + ($b * $x);
			$a = $b;
			$b = $t;
			$t = ($c * $n) + ($d * $x);
			$c = $d;
			$d = $t;
			$n += 0.5;
			$q1 = $q2;
			$q2 = $b / $d;
		} while (PRECISION < (abs($q1 - $q2) / $q2));

		return self::$_one_sqrtpi * exp((0 - $x) * $x) * $q2;
	}

	static public function ERFC($x)
	{
		$x = self::flattenSingleValue($x);

		if (is_numeric($x)) {
			if ($x < 0) {
				return self::$_errorCodes['num'];
			}

			return self::_erfcVal($x);
		}

		return self::$_errorCodes['value'];
	}

	static public function LOWERCASE($mixedCaseString)
	{
		$mixedCaseString = self::flattenSingleValue($mixedCaseString);

		if (is_bool($mixedCaseString)) {
			$mixedCaseString = ($mixedCaseString ? 'TRUE' : 'FALSE');
		}

		if (function_exists('mb_convert_case')) {
			return mb_convert_case($mixedCaseString, MB_CASE_LOWER, 'UTF-8');
		}
		else {
			return strtoupper($mixedCaseString);
		}
	}

	static public function UPPERCASE($mixedCaseString)
	{
		$mixedCaseString = self::flattenSingleValue($mixedCaseString);

		if (is_bool($mixedCaseString)) {
			$mixedCaseString = ($mixedCaseString ? 'TRUE' : 'FALSE');
		}

		if (function_exists('mb_convert_case')) {
			return mb_convert_case($mixedCaseString, MB_CASE_UPPER, 'UTF-8');
		}
		else {
			return strtoupper($mixedCaseString);
		}
	}

	static public function PROPERCASE($mixedCaseString)
	{
		$mixedCaseString = self::flattenSingleValue($mixedCaseString);

		if (is_bool($mixedCaseString)) {
			$mixedCaseString = ($mixedCaseString ? 'TRUE' : 'FALSE');
		}

		if (function_exists('mb_convert_case')) {
			return mb_convert_case($mixedCaseString, MB_CASE_TITLE, 'UTF-8');
		}
		else {
			return ucwords($mixedCaseString);
		}
	}

	static public function DOLLAR($value = 0, $decimals = 2)
	{
		$value = self::flattenSingleValue($value);
		$decimals = (is_null($decimals) ? 0 : self::flattenSingleValue($decimals));
		if (!is_numeric($value) || !is_numeric($decimals)) {
			return self::$_errorCodes['num'];
		}

		$decimals = floor($decimals);

		if (0 < $decimals) {
			return money_format('%.' . $decimals . 'n', $value);
		}
		else {
			$round = pow(10, abs($decimals));

			if ($value < 0) {
				$round = 0 - $round;
			}

			$value = self::MROUND($value, $round);
			return substr(money_format('%.1n', $value), 0, -2);
		}
	}

	static public function DOLLARDE($fractional_dollar = NULL, $fraction = 0)
	{
		$fractional_dollar = self::flattenSingleValue($fractional_dollar);
		$fraction = (int) self::flattenSingleValue($fraction);
		if (is_null($fractional_dollar) || ($fraction < 0)) {
			return self::$_errorCodes['num'];
		}

		if ($fraction == 0) {
			return self::$_errorCodes['divisionbyzero'];
		}

		$dollars = floor($fractional_dollar);
		$cents = fmod($fractional_dollar, 1);
		$cents /= $fraction;
		$cents *= pow(10, ceil(log10($fraction)));
		return $dollars + $cents;
	}

	static public function DOLLARFR($decimal_dollar = NULL, $fraction = 0)
	{
		$decimal_dollar = self::flattenSingleValue($decimal_dollar);
		$fraction = (int) self::flattenSingleValue($fraction);
		if (is_null($decimal_dollar) || ($fraction < 0)) {
			return self::$_errorCodes['num'];
		}

		if ($fraction == 0) {
			return self::$_errorCodes['divisionbyzero'];
		}

		$dollars = floor($decimal_dollar);
		$cents = fmod($decimal_dollar, 1);
		$cents *= $fraction;
		$cents *= pow(10, 0 - ceil(log10($fraction)));
		return $dollars + $cents;
	}

	static public function EFFECT($nominal_rate = 0, $npery = 0)
	{
		$nominal_rate = self::flattenSingleValue($nominal_rate);
		$npery = (int) self::flattenSingleValue($npery);
		if (($nominal_rate <= 0) || ($npery < 1)) {
			return self::$_errorCodes['num'];
		}

		return pow(1 + ($nominal_rate / $npery), $npery) - 1;
	}

	static public function NOMINAL($effect_rate = 0, $npery = 0)
	{
		$effect_rate = self::flattenSingleValue($effect_rate);
		$npery = (int) self::flattenSingleValue($npery);
		if (($effect_rate <= 0) || ($npery < 1)) {
			return self::$_errorCodes['num'];
		}

		return $npery * (pow($effect_rate + 1, 1 / $npery) - 1);
	}

	static public function PV($rate = 0, $nper = 0, $pmt = 0, $fv = 0, $type = 0)
	{
		$rate = self::flattenSingleValue($rate);
		$nper = self::flattenSingleValue($nper);
		$pmt = self::flattenSingleValue($pmt);
		$fv = self::flattenSingleValue($fv);
		$type = self::flattenSingleValue($type);
		if (($type != 0) && ($type != 1)) {
			return self::$_errorCodes['num'];
		}

		if (!is_null($rate) && ($rate != 0)) {
			return (((0 - $pmt) * (1 + ($rate * $type)) * ((pow(1 + $rate, $nper) - 1) / $rate)) - $fv) / pow(1 + $rate, $nper);
		}
		else {
			return 0 - $fv - ($pmt * $nper);
		}
	}

	static public function FV($rate = 0, $nper = 0, $pmt = 0, $pv = 0, $type = 0)
	{
		$rate = self::flattenSingleValue($rate);
		$nper = self::flattenSingleValue($nper);
		$pmt = self::flattenSingleValue($pmt);
		$pv = self::flattenSingleValue($pv);
		$type = self::flattenSingleValue($type);
		if (($type != 0) && ($type != 1)) {
			return self::$_errorCodes['num'];
		}

		if (!is_null($rate) && ($rate != 0)) {
			return ((0 - $pv) * pow(1 + $rate, $nper)) - (($pmt * (1 + ($rate * $type)) * (pow(1 + $rate, $nper) - 1)) / $rate);
		}
		else {
			return 0 - $pv - ($pmt * $nper);
		}
	}

	static public function FVSCHEDULE($principal, $schedule)
	{
		$principal = self::flattenSingleValue($principal);
		$schedule = self::flattenArray($schedule);

		foreach ($schedule as $n) {
			$principal *= 1 + $n;
		}

		return $principal;
	}

	static public function PMT($rate = 0, $nper = 0, $pv = 0, $fv = 0, $type = 0)
	{
		$rate = self::flattenSingleValue($rate);
		$nper = self::flattenSingleValue($nper);
		$pv = self::flattenSingleValue($pv);
		$fv = self::flattenSingleValue($fv);
		$type = self::flattenSingleValue($type);
		if (($type != 0) && ($type != 1)) {
			return self::$_errorCodes['num'];
		}

		if (!is_null($rate) && ($rate != 0)) {
			return (0 - $fv - ($pv * pow(1 + $rate, $nper))) / (1 + ($rate * $type)) / (pow(1 + $rate, $nper) - 1) / $rate;
		}
		else {
			return (0 - $pv - $fv) / $nper;
		}
	}

	static public function NPER($rate = 0, $pmt = 0, $pv = 0, $fv = 0, $type = 0)
	{
		$rate = self::flattenSingleValue($rate);
		$pmt = self::flattenSingleValue($pmt);
		$pv = self::flattenSingleValue($pv);
		$fv = self::flattenSingleValue($fv);
		$type = self::flattenSingleValue($type);
		if (($type != 0) && ($type != 1)) {
			return self::$_errorCodes['num'];
		}

		if (!is_null($rate) && ($rate != 0)) {
			if (($pmt == 0) && ($pv == 0)) {
				return self::$_errorCodes['num'];
			}

			return log(((($pmt * (1 + ($rate * $type))) / $rate) - $fv) / ($pv + (($pmt * (1 + ($rate * $type))) / $rate))) / log(1 + $rate);
		}
		else {
			if ($pmt == 0) {
				return self::$_errorCodes['num'];
			}

			return (0 - $pv - $fv) / $pmt;
		}
	}

	static private function _interestAndPrincipal($rate = 0, $per = 0, $nper = 0, $pv = 0, $fv = 0, $type = 0)
	{
		$pmt = self::PMT($rate, $nper, $pv, $fv, $type);
		$capital = $pv;

		for ($i = 1; $i <= $per; ++$i) {
			$interest = ($type && ($i == 1) ? 0 : (0 - $capital) * $rate);
			$principal = $pmt - $interest;
			$capital += $principal;
		}

		return array($interest, $principal);
	}

	static public function IPMT($rate, $per, $nper, $pv, $fv = 0, $type = 0)
	{
		$rate = self::flattenSingleValue($rate);
		$per = (int) self::flattenSingleValue($per);
		$nper = (int) self::flattenSingleValue($nper);
		$pv = self::flattenSingleValue($pv);
		$fv = self::flattenSingleValue($fv);
		$type = (int) self::flattenSingleValue($type);
		if (($type != 0) && ($type != 1)) {
			return self::$_errorCodes['num'];
		}

		if (($per <= 0) || ($nper < $per)) {
			return self::$_errorCodes['value'];
		}

		$interestAndPrincipal = self::_interestAndPrincipal($rate, $per, $nper, $pv, $fv, $type);
		return $interestAndPrincipal[0];
	}

	static public function CUMIPMT($rate, $nper, $pv, $start, $end, $type = 0)
	{
		$rate = self::flattenSingleValue($rate);
		$nper = (int) self::flattenSingleValue($nper);
		$pv = self::flattenSingleValue($pv);
		$start = (int) self::flattenSingleValue($start);
		$end = (int) self::flattenSingleValue($end);
		$type = (int) self::flattenSingleValue($type);
		if (($type != 0) && ($type != 1)) {
			return self::$_errorCodes['num'];
		}

		if (($start < 1) || ($end < $start)) {
			return self::$_errorCodes['value'];
		}

		$interest = 0;

		for ($per = $start; $per <= $end; ++$per) {
			$interest += self::IPMT($rate, $per, $nper, $pv, 0, $type);
		}

		return $interest;
	}

	static public function PPMT($rate, $per, $nper, $pv, $fv = 0, $type = 0)
	{
		$rate = self::flattenSingleValue($rate);
		$per = (int) self::flattenSingleValue($per);
		$nper = (int) self::flattenSingleValue($nper);
		$pv = self::flattenSingleValue($pv);
		$fv = self::flattenSingleValue($fv);
		$type = (int) self::flattenSingleValue($type);
		if (($type != 0) && ($type != 1)) {
			return self::$_errorCodes['num'];
		}

		if (($per <= 0) || ($nper < $per)) {
			return self::$_errorCodes['value'];
		}

		$interestAndPrincipal = self::_interestAndPrincipal($rate, $per, $nper, $pv, $fv, $type);
		return $interestAndPrincipal[1];
	}

	static public function CUMPRINC($rate, $nper, $pv, $start, $end, $type = 0)
	{
		$rate = self::flattenSingleValue($rate);
		$nper = (int) self::flattenSingleValue($nper);
		$pv = self::flattenSingleValue($pv);
		$start = (int) self::flattenSingleValue($start);
		$end = (int) self::flattenSingleValue($end);
		$type = (int) self::flattenSingleValue($type);
		if (($type != 0) && ($type != 1)) {
			return self::$_errorCodes['num'];
		}

		if (($start < 1) || ($end < $start)) {
			return self::$_errorCodes['value'];
		}

		$principal = 0;

		for ($per = $start; $per <= $end; ++$per) {
			$principal += self::PPMT($rate, $per, $nper, $pv, 0, $type);
		}

		return $principal;
	}

	static public function ISPMT()
	{
		$returnValue = 0;
		$aArgs = self::flattenArray(func_get_args());
		$interestRate = array_shift($aArgs);
		$period = array_shift($aArgs);
		$numberPeriods = array_shift($aArgs);
		$principleRemaining = array_shift($aArgs);
		$principlePayment = ($principleRemaining * 1) / ($numberPeriods * 1);

		for ($i = 0; $i <= $period; ++$i) {
			$returnValue = $interestRate * $principleRemaining * -1;
			$principleRemaining -= $principlePayment;

			if ($i == $numberPeriods) {
				$returnValue = 0;
			}
		}

		return $returnValue;
	}

	static public function NPV()
	{
		$returnValue = 0;
		$aArgs = self::flattenArray(func_get_args());
		$rate = array_shift($aArgs);

		for ($i = 1; $i <= count($aArgs); ++$i) {
			if (is_numeric($aArgs[$i - 1])) {
				$returnValue += $aArgs[$i - 1] / pow(1 + $rate, $i);
			}
		}

		return $returnValue;
	}

	static public function XNPV($rate, $values, $dates)
	{
		if (!is_array($values) || !is_array($dates)) {
			return self::$_errorCodes['value'];
		}

		$values = self::flattenArray($values);
		$dates = self::flattenArray($dates);
		$valCount = count($values);

		if ($valCount != count($dates)) {
			return self::$_errorCodes['num'];
		}

		$xnpv = 0;

		for ($i = 0; $i < $valCount; ++$i) {
			$xnpv += $values[$i] / pow(1 + $rate, self::DATEDIF($dates[0], $dates[$i], 'd') / 365);
		}

		return is_finite($xnpv) ? $xnpv : self::$_errorCodes['value'];
	}

	static public function IRR($values, $guess = 0.10000000000000001)
	{
		if (!is_array($values)) {
			return self::$_errorCodes['value'];
		}

		$values = self::flattenArray($values);
		$guess = self::flattenSingleValue($guess);
		$x1 = 0;
		$x2 = $guess;
		$f1 = self::NPV($x1, $values);
		$f2 = self::NPV($x2, $values);

		for ($i = 0; $i < FINANCIAL_MAX_ITERATIONS; ++$i) {
			if (($f1 * $f2) < 0) {
				break;
			}

			if (abs($f1) < abs($f2)) {
				$f1 = self::NPV($x1 += 1.6000000000000001 * ($x1 - $x2), $values);
			}
			else {
				$f2 = self::NPV($x2 += 1.6000000000000001 * ($x2 - $x1), $values);
			}
		}

		if (0 < ($f1 * $f2)) {
			return self::$_errorCodes['value'];
		}

		$f = self::NPV($x1, $values);

		if ($f < 0) {
			$rtb = $x1;
			$dx = $x2 - $x1;
		}
		else {
			$rtb = $x2;
			$dx = $x1 - $x2;
		}

		for ($i = 0; $i < FINANCIAL_MAX_ITERATIONS; ++$i) {
			$dx *= 0.5;
			$x_mid = $rtb + $dx;
			$f_mid = self::NPV($x_mid, $values);

			if ($f_mid <= 0) {
				$rtb = $x_mid;
			}

			if ((abs($f_mid) < FINANCIAL_PRECISION) || (abs($dx) < FINANCIAL_PRECISION)) {
				return $x_mid;
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function MIRR($values, $finance_rate, $reinvestment_rate)
	{
		if (!is_array($values)) {
			return self::$_errorCodes['value'];
		}

		$values = self::flattenArray($values);
		$finance_rate = self::flattenSingleValue($finance_rate);
		$reinvestment_rate = self::flattenSingleValue($reinvestment_rate);
		$n = count($values);
		$rr = 1 + $reinvestment_rate;
		$fr = 1 + $finance_rate;
		$npv_pos = $npv_neg = 0;

		foreach ($values as $i => $v) {
			if (0 <= $v) {
				$npv_pos += $v / pow($rr, $i);
			}
			else {
				$npv_neg += $v / pow($fr, $i);
			}
		}

		if (($npv_neg == 0) || ($npv_pos == 0) || ($reinvestment_rate <= -1)) {
			return self::$_errorCodes['value'];
		}

		$mirr = pow(((0 - $npv_pos) * pow($rr, $n)) / ($npv_neg * $rr), 1 / ($n - 1)) - 1;
		return is_finite($mirr) ? $mirr : self::$_errorCodes['value'];
	}

	static public function XIRR($values, $dates, $guess = 0.10000000000000001)
	{
		if (!is_array($values) && !is_array($dates)) {
			return self::$_errorCodes['value'];
		}

		$values = self::flattenArray($values);
		$dates = self::flattenArray($dates);
		$guess = self::flattenSingleValue($guess);

		if (count($values) != count($dates)) {
			return self::$_errorCodes['num'];
		}

		$x1 = 0;
		$x2 = $guess;
		$f1 = self::XNPV($x1, $values, $dates);
		$f2 = self::XNPV($x2, $values, $dates);

		for ($i = 0; $i < FINANCIAL_MAX_ITERATIONS; ++$i) {
			if (($f1 * $f2) < 0) {
				break;
			}

			if (abs($f1) < abs($f2)) {
				$f1 = self::XNPV($x1 += 1.6000000000000001 * ($x1 - $x2), $values, $dates);
			}
			else {
				$f2 = self::XNPV($x2 += 1.6000000000000001 * ($x2 - $x1), $values, $dates);
			}
		}

		if (0 < ($f1 * $f2)) {
			return self::$_errorCodes['value'];
		}

		$f = self::XNPV($x1, $values, $dates);

		if ($f < 0) {
			$rtb = $x1;
			$dx = $x2 - $x1;
		}
		else {
			$rtb = $x2;
			$dx = $x1 - $x2;
		}

		for ($i = 0; $i < FINANCIAL_MAX_ITERATIONS; ++$i) {
			$dx *= 0.5;
			$x_mid = $rtb + $dx;
			$f_mid = self::XNPV($x_mid, $values, $dates);

			if ($f_mid <= 0) {
				$rtb = $x_mid;
			}

			if ((abs($f_mid) < FINANCIAL_PRECISION) || (abs($dx) < FINANCIAL_PRECISION)) {
				return $x_mid;
			}
		}

		return self::$_errorCodes['value'];
	}

	static public function RATE($nper, $pmt, $pv, $fv = 0, $type = 0, $guess = 0.10000000000000001)
	{
		$nper = (int) self::flattenSingleValue($nper);
		$pmt = self::flattenSingleValue($pmt);
		$pv = self::flattenSingleValue($pv);
		$fv = (is_null($fv) ? 0 : self::flattenSingleValue($fv));
		$type = (is_null($type) ? 0 : (int) self::flattenSingleValue($type));
		$guess = (is_null($guess) ? 0.10000000000000001 : self::flattenSingleValue($guess));
		$rate = $guess;

		if (abs($rate) < FINANCIAL_PRECISION) {
			$y = ($pv * (1 + ($nper * $rate))) + ($pmt * (1 + ($rate * $type)) * $nper) + $fv;
		}
		else {
			$f = exp($nper * log(1 + $rate));
			$y = ($pv * $f) + ($pmt * ((1 / $rate) + $type) * ($f - 1)) + $fv;
		}

		$y0 = $pv + ($pmt * $nper) + $fv;
		$y1 = ($pv * $f) + ($pmt * ((1 / $rate) + $type) * ($f - 1)) + $fv;
		$i = $x0 = 0;
		$x1 = $rate;

		while ($i < FINANCIAL_MAX_ITERATIONS) {
			$rate = (($y1 * $x0) - ($y0 * $x1)) / ($y1 - $y0);
			$x0 = $x1;
			$x1 = $rate;

			if (abs($rate) < FINANCIAL_PRECISION) {
				$y = ($pv * (1 + ($nper * $rate))) + ($pmt * (1 + ($rate * $type)) * $nper) + $fv;
			}
			else {
				$f = exp($nper * log(1 + $rate));
				$y = ($pv * $f) + ($pmt * ((1 / $rate) + $type) * ($f - 1)) + $fv;
			}

			$y0 = $y1;
			$y1 = $y;
			++$i;
		}

		return $rate;
	}

	static public function DB($cost, $salvage, $life, $period, $month = 12)
	{
		$cost = (double) self::flattenSingleValue($cost);
		$salvage = (double) self::flattenSingleValue($salvage);
		$life = (int) self::flattenSingleValue($life);
		$period = (int) self::flattenSingleValue($period);
		$month = (int) self::flattenSingleValue($month);
		if (is_numeric($cost) && is_numeric($salvage) && is_numeric($life) && is_numeric($period) && is_numeric($month)) {
			if ($cost == 0) {
				return 0;
			}
			else {
				if (($cost < 0) || (($salvage / $cost) < 0) || ($life <= 0) || ($period < 1) || ($month < 1)) {
					return self::$_errorCodes['num'];
				}
			}

			$fixedDepreciationRate = 1 - pow($salvage / $cost, 1 / $life);
			$fixedDepreciationRate = round($fixedDepreciationRate, 3);
			$previousDepreciation = 0;

			for ($per = 1; $per <= $period; ++$per) {
				if ($per == 1) {
					$depreciation = ($cost * $fixedDepreciationRate * $month) / 12;
				}
				else if ($per == ($life + 1)) {
					$depreciation = (($cost - $previousDepreciation) * $fixedDepreciationRate * (12 - $month)) / 12;
				}
				else {
					$depreciation = ($cost - $previousDepreciation) * $fixedDepreciationRate;
				}

				$previousDepreciation += $depreciation;
			}

			if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
				$depreciation = round($depreciation, 2);
			}

			return $depreciation;
		}

		return self::$_errorCodes['value'];
	}

	static public function DDB($cost, $salvage, $life, $period, $factor = 2)
	{
		$cost = (double) self::flattenSingleValue($cost);
		$salvage = (double) self::flattenSingleValue($salvage);
		$life = (int) self::flattenSingleValue($life);
		$period = (int) self::flattenSingleValue($period);
		$factor = (double) self::flattenSingleValue($factor);
		if (is_numeric($cost) && is_numeric($salvage) && is_numeric($life) && is_numeric($period) && is_numeric($factor)) {
			if (($cost <= 0) || (($salvage / $cost) < 0) || ($life <= 0) || ($period < 1) || ($factor <= 0) || ($life < $period)) {
				return self::$_errorCodes['num'];
			}

			$fixedDepreciationRate = 1 - pow($salvage / $cost, 1 / $life);
			$fixedDepreciationRate = round($fixedDepreciationRate, 3);
			$previousDepreciation = 0;

			for ($per = 1; $per <= $period; ++$per) {
				$depreciation = min(($cost - $previousDepreciation) * ($factor / $life), $cost - $salvage - $previousDepreciation);
				$previousDepreciation += $depreciation;
			}

			if (self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) {
				$depreciation = round($depreciation, 2);
			}

			return $depreciation;
		}

		return self::$_errorCodes['value'];
	}

	static private function _daysPerYear($year, $basis)
	{
		switch ($basis) {
		case 0:
		case 2:
		case 4:
			$daysPerYear = 360;
			break;

		case 3:
			$daysPerYear = 365;
			break;

		case 1:
			if (self::_isLeapYear($year)) {
				$daysPerYear = 366;
			}
			else {
				$daysPerYear = 365;
			}

			break;

		default:
			return self::$_errorCodes['num'];
		}

		return $daysPerYear;
	}

	static public function ACCRINT($issue, $firstinter, $settlement, $rate, $par = 1000, $frequency = 1, $basis = 0)
	{
		$issue = self::flattenSingleValue($issue);
		$firstinter = self::flattenSingleValue($firstinter);
		$settlement = self::flattenSingleValue($settlement);
		$rate = (double) self::flattenSingleValue($rate);
		$par = (is_null($par) ? 1000 : (double) self::flattenSingleValue($par));
		$frequency = (is_null($frequency) ? 1 : (int) self::flattenSingleValue($frequency));
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));
		if (is_numeric($rate) && is_numeric($par)) {
			if (($rate <= 0) || ($par <= 0)) {
				return self::$_errorCodes['num'];
			}

			$daysBetweenIssueAndSettlement = self::YEARFRAC($issue, $settlement, $basis);

			if (!is_numeric($daysBetweenIssueAndSettlement)) {
				return $daysBetweenIssueAndSettlement;
			}

			return $par * $rate * $daysBetweenIssueAndSettlement;
		}

		return self::$_errorCodes['value'];
	}

	static public function ACCRINTM($issue, $settlement, $rate, $par = 1000, $basis = 0)
	{
		$issue = self::flattenSingleValue($issue);
		$settlement = self::flattenSingleValue($settlement);
		$rate = (double) self::flattenSingleValue($rate);
		$par = (is_null($par) ? 1000 : (double) self::flattenSingleValue($par));
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));
		if (is_numeric($rate) && is_numeric($par)) {
			if (($rate <= 0) || ($par <= 0)) {
				return self::$_errorCodes['num'];
			}

			$daysBetweenIssueAndSettlement = self::YEARFRAC($issue, $settlement, $basis);

			if (!is_numeric($daysBetweenIssueAndSettlement)) {
				return $daysBetweenIssueAndSettlement;
			}

			return $par * $rate * $daysBetweenIssueAndSettlement;
		}

		return self::$_errorCodes['value'];
	}

	static public function AMORDEGRC($cost, $purchased, $firstPeriod, $salvage, $period, $rate, $basis = 0)
	{
		$cost = self::flattenSingleValue($cost);
		$purchased = self::flattenSingleValue($purchased);
		$firstPeriod = self::flattenSingleValue($firstPeriod);
		$salvage = self::flattenSingleValue($salvage);
		$period = floor(self::flattenSingleValue($period));
		$rate = self::flattenSingleValue($rate);
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));
		$fUsePer = 1 / $rate;

		if ($fUsePer < 3) {
			$amortiseCoeff = 1;
		}
		else if ($fUsePer < 5) {
			$amortiseCoeff = 1.5;
		}
		else if ($fUsePer <= 6) {
			$amortiseCoeff = 2;
		}
		else {
			$amortiseCoeff = 2.5;
		}

		$rate *= $amortiseCoeff;
		$fNRate = round(self::YEARFRAC($purchased, $firstPeriod, $basis) * $rate * $cost, 0);
		$cost -= $fNRate;
		$fRest = $cost - $salvage;

		for ($n = 0; $n < $period; ++$n) {
			$fNRate = round($rate * $cost, 0);
			$fRest -= $fNRate;

			if ($fRest < 0) {
				switch ($period - $n) {
				case 0:
				case 1:
					return round($cost * 0.5, 0);
					break;

				default:
					return 0;
					break;
				}
			}

			$cost -= $fNRate;
		}

		return $fNRate;
	}

	static public function AMORLINC($cost, $purchased, $firstPeriod, $salvage, $period, $rate, $basis = 0)
	{
		$cost = self::flattenSingleValue($cost);
		$purchased = self::flattenSingleValue($purchased);
		$firstPeriod = self::flattenSingleValue($firstPeriod);
		$salvage = self::flattenSingleValue($salvage);
		$period = self::flattenSingleValue($period);
		$rate = self::flattenSingleValue($rate);
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));
		$fOneRate = $cost * $rate;
		$fCostDelta = $cost - $salvage;
		$purchasedYear = self::YEAR($purchased);
		$yearFrac = self::YEARFRAC($purchased, $firstPeriod, $basis);
		if (($basis == 1) && ($yearFrac < 1) && self::_isLeapYear($purchasedYear)) {
			$yearFrac *= 365 / 366;
		}

		$f0Rate = $yearFrac * $rate * $cost;
		$nNumOfFullPeriods = intval(($cost - $salvage - $f0Rate) / $fOneRate);

		if ($period == 0) {
			return $f0Rate;
		}
		else if ($period <= $nNumOfFullPeriods) {
			return $fOneRate;
		}
		else if ($period == ($nNumOfFullPeriods + 1)) {
			return $fCostDelta - ($fOneRate * $nNumOfFullPeriods) - $f0Rate;
		}
		else {
			return 0;
		}
	}

	static private function _lastDayOfMonth($testDate)
	{
		$date = clone $testDate;
		$date->modify('+1 day');
		return $date->format('d') == 1;
	}

	static private function _firstDayOfMonth($testDate)
	{
		$date = clone $testDate;
		return $date->format('d') == 1;
	}

	static private function _coupFirstPeriodDate($settlement, $maturity, $frequency, $next)
	{
		$months = 12 / $frequency;
		$result = PHPExcel_Shared_Date::ExcelToPHPObject($maturity);
		$eom = self::_lastDayOfMonth($result);

		while ($settlement < PHPExcel_Shared_Date::PHPToExcel($result)) {
			$result->modify('-' . $months . ' months');
		}

		if ($next) {
			$result->modify('+' . $months . ' months');
		}

		if ($eom) {
			$result->modify('-1 day');
		}

		return PHPExcel_Shared_Date::PHPToExcel($result);
	}

	static private function _validFrequency($frequency)
	{
		if (($frequency == 1) || ($frequency == 2) || ($frequency == 4)) {
			return true;
		}

		if ((self::$compatibilityMode == self::COMPATIBILITY_GNUMERIC) && (($frequency == 6) || ($frequency == 12))) {
			return true;
		}

		return false;
	}

	static public function COUPDAYS($settlement, $maturity, $frequency, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$frequency = (int) self::flattenSingleValue($frequency);
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));

		if (is_string($settlement = self::_getDateValue($settlement))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($maturity = self::_getDateValue($maturity))) {
			return self::$_errorCodes['value'];
		}

		if (($maturity < $settlement) || !self::_validFrequency($frequency) || ($basis < 0) || (4 < $basis)) {
			return self::$_errorCodes['num'];
		}

		switch ($basis) {
		case 3:
			return 365 / $frequency;
		case 1:
			if ($frequency == 1) {
				$daysPerYear = self::_daysPerYear(self::YEAR($maturity), $basis);
				return $daysPerYear / $frequency;
			}
			else {
				$prev = self::_coupFirstPeriodDate($settlement, $maturity, $frequency, false);
				$next = self::_coupFirstPeriodDate($settlement, $maturity, $frequency, true);
				return $next - $prev;
			}
		default:
			return 360 / $frequency;
		}

		return self::$_errorCodes['value'];
	}

	static public function COUPDAYBS($settlement, $maturity, $frequency, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$frequency = (int) self::flattenSingleValue($frequency);
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));

		if (is_string($settlement = self::_getDateValue($settlement))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($maturity = self::_getDateValue($maturity))) {
			return self::$_errorCodes['value'];
		}

		if (($maturity < $settlement) || !self::_validFrequency($frequency) || ($basis < 0) || (4 < $basis)) {
			return self::$_errorCodes['num'];
		}

		$daysPerYear = self::_daysPerYear(self::YEAR($settlement), $basis);
		$prev = self::_coupFirstPeriodDate($settlement, $maturity, $frequency, false);
		return self::YEARFRAC($prev, $settlement, $basis) * $daysPerYear;
	}

	static public function COUPDAYSNC($settlement, $maturity, $frequency, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$frequency = (int) self::flattenSingleValue($frequency);
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));

		if (is_string($settlement = self::_getDateValue($settlement))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($maturity = self::_getDateValue($maturity))) {
			return self::$_errorCodes['value'];
		}

		if (($maturity < $settlement) || !self::_validFrequency($frequency) || ($basis < 0) || (4 < $basis)) {
			return self::$_errorCodes['num'];
		}

		$daysPerYear = self::_daysPerYear(self::YEAR($settlement), $basis);
		$next = self::_coupFirstPeriodDate($settlement, $maturity, $frequency, true);
		return self::YEARFRAC($settlement, $next, $basis) * $daysPerYear;
	}

	static public function COUPNCD($settlement, $maturity, $frequency, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$frequency = (int) self::flattenSingleValue($frequency);
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));

		if (is_string($settlement = self::_getDateValue($settlement))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($maturity = self::_getDateValue($maturity))) {
			return self::$_errorCodes['value'];
		}

		if (($maturity < $settlement) || !self::_validFrequency($frequency) || ($basis < 0) || (4 < $basis)) {
			return self::$_errorCodes['num'];
		}

		return self::_coupFirstPeriodDate($settlement, $maturity, $frequency, true);
	}

	static public function COUPPCD($settlement, $maturity, $frequency, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$frequency = (int) self::flattenSingleValue($frequency);
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));

		if (is_string($settlement = self::_getDateValue($settlement))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($maturity = self::_getDateValue($maturity))) {
			return self::$_errorCodes['value'];
		}

		if (($maturity < $settlement) || !self::_validFrequency($frequency) || ($basis < 0) || (4 < $basis)) {
			return self::$_errorCodes['num'];
		}

		return self::_coupFirstPeriodDate($settlement, $maturity, $frequency, false);
	}

	static public function COUPNUM($settlement, $maturity, $frequency, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$frequency = (int) self::flattenSingleValue($frequency);
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));

		if (is_string($settlement = self::_getDateValue($settlement))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($maturity = self::_getDateValue($maturity))) {
			return self::$_errorCodes['value'];
		}

		if (($maturity < $settlement) || !self::_validFrequency($frequency) || ($basis < 0) || (4 < $basis)) {
			return self::$_errorCodes['num'];
		}

		$settlement = self::_coupFirstPeriodDate($settlement, $maturity, $frequency, true);
		$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity, $basis) * 365;

		switch ($frequency) {
		case 1:
			return ceil($daysBetweenSettlementAndMaturity / 360);
		case 2:
			return ceil($daysBetweenSettlementAndMaturity / 180);
		case 4:
			return ceil($daysBetweenSettlementAndMaturity / 90);
		case 6:
			return ceil($daysBetweenSettlementAndMaturity / 60);
		case 12:
			return ceil($daysBetweenSettlementAndMaturity / 30);
		}

		return self::$_errorCodes['value'];
	}

	static public function PRICE($settlement, $maturity, $rate, $yield, $redemption, $frequency, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$rate = (double) self::flattenSingleValue($rate);
		$yield = (double) self::flattenSingleValue($yield);
		$redemption = (double) self::flattenSingleValue($redemption);
		$frequency = (int) self::flattenSingleValue($frequency);
		$basis = (is_null($basis) ? 0 : (int) self::flattenSingleValue($basis));

		if (is_string($settlement = self::_getDateValue($settlement))) {
			return self::$_errorCodes['value'];
		}

		if (is_string($maturity = self::_getDateValue($maturity))) {
			return self::$_errorCodes['value'];
		}

		if (($maturity < $settlement) || !self::_validFrequency($frequency) || ($basis < 0) || (4 < $basis)) {
			return self::$_errorCodes['num'];
		}

		$dsc = self::COUPDAYSNC($settlement, $maturity, $frequency, $basis);
		$e = self::COUPDAYS($settlement, $maturity, $frequency, $basis);
		$n = self::COUPNUM($settlement, $maturity, $frequency, $basis);
		$a = self::COUPDAYBS($settlement, $maturity, $frequency, $basis);
		$baseYF = 1 + ($yield / $frequency);
		$rfp = 100 * ($rate / $frequency);
		$de = $dsc / $e;
		$result = $redemption / pow($baseYF, --$n + $de);

		for ($k = 0; $k <= $n; ++$k) {
			$result += $rfp / pow($baseYF, $k + $de);
		}

		$result -= $rfp * ($a / $e);
		return $result;
	}

	static public function DISC($settlement, $maturity, $price, $redemption, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$price = (double) self::flattenSingleValue($price);
		$redemption = (double) self::flattenSingleValue($redemption);
		$basis = (int) self::flattenSingleValue($basis);
		if (is_numeric($price) && is_numeric($redemption) && is_numeric($basis)) {
			if (($price <= 0) || ($redemption <= 0)) {
				return self::$_errorCodes['num'];
			}

			$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity, $basis);

			if (!is_numeric($daysBetweenSettlementAndMaturity)) {
				return $daysBetweenSettlementAndMaturity;
			}

			return (1 - ($price / $redemption)) / $daysBetweenSettlementAndMaturity;
		}

		return self::$_errorCodes['value'];
	}

	static public function PRICEDISC($settlement, $maturity, $discount, $redemption, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$discount = (double) self::flattenSingleValue($discount);
		$redemption = (double) self::flattenSingleValue($redemption);
		$basis = (int) self::flattenSingleValue($basis);
		if (is_numeric($discount) && is_numeric($redemption) && is_numeric($basis)) {
			if (($discount <= 0) || ($redemption <= 0)) {
				return self::$_errorCodes['num'];
			}

			$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity, $basis);

			if (!is_numeric($daysBetweenSettlementAndMaturity)) {
				return $daysBetweenSettlementAndMaturity;
			}

			return $redemption * (1 - ($discount * $daysBetweenSettlementAndMaturity));
		}

		return self::$_errorCodes['value'];
	}

	static public function PRICEMAT($settlement, $maturity, $issue, $rate, $yield, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$issue = self::flattenSingleValue($issue);
		$rate = self::flattenSingleValue($rate);
		$yield = self::flattenSingleValue($yield);
		$basis = (int) self::flattenSingleValue($basis);
		if (is_numeric($rate) && is_numeric($yield)) {
			if (($rate <= 0) || ($yield <= 0)) {
				return self::$_errorCodes['num'];
			}

			$daysPerYear = self::_daysPerYear(self::YEAR($settlement), $basis);

			if (!is_numeric($daysPerYear)) {
				return $daysPerYear;
			}

			$daysBetweenIssueAndSettlement = self::YEARFRAC($issue, $settlement, $basis);

			if (!is_numeric($daysBetweenIssueAndSettlement)) {
				return $daysBetweenIssueAndSettlement;
			}

			$daysBetweenIssueAndSettlement *= $daysPerYear;
			$daysBetweenIssueAndMaturity = self::YEARFRAC($issue, $maturity, $basis);

			if (!is_numeric($daysBetweenIssueAndMaturity)) {
				return $daysBetweenIssueAndMaturity;
			}

			$daysBetweenIssueAndMaturity *= $daysPerYear;
			$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity, $basis);

			if (!is_numeric($daysBetweenSettlementAndMaturity)) {
				return $daysBetweenSettlementAndMaturity;
			}

			$daysBetweenSettlementAndMaturity *= $daysPerYear;
			return ((100 + (($daysBetweenIssueAndMaturity / $daysPerYear) * $rate * 100)) / (1 + (($daysBetweenSettlementAndMaturity / $daysPerYear) * $yield))) - (($daysBetweenIssueAndSettlement / $daysPerYear) * $rate * 100);
		}

		return self::$_errorCodes['value'];
	}

	static public function RECEIVED($settlement, $maturity, $investment, $discount, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$investment = (double) self::flattenSingleValue($investment);
		$discount = (double) self::flattenSingleValue($discount);
		$basis = (int) self::flattenSingleValue($basis);
		if (is_numeric($investment) && is_numeric($discount) && is_numeric($basis)) {
			if (($investment <= 0) || ($discount <= 0)) {
				return self::$_errorCodes['num'];
			}

			$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity, $basis);

			if (!is_numeric($daysBetweenSettlementAndMaturity)) {
				return $daysBetweenSettlementAndMaturity;
			}

			return $investment / (1 - ($discount * $daysBetweenSettlementAndMaturity));
		}

		return self::$_errorCodes['value'];
	}

	static public function INTRATE($settlement, $maturity, $investment, $redemption, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$investment = (double) self::flattenSingleValue($investment);
		$redemption = (double) self::flattenSingleValue($redemption);
		$basis = (int) self::flattenSingleValue($basis);
		if (is_numeric($investment) && is_numeric($redemption) && is_numeric($basis)) {
			if (($investment <= 0) || ($redemption <= 0)) {
				return self::$_errorCodes['num'];
			}

			$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity, $basis);

			if (!is_numeric($daysBetweenSettlementAndMaturity)) {
				return $daysBetweenSettlementAndMaturity;
			}

			return (($redemption / $investment) - 1) / $daysBetweenSettlementAndMaturity;
		}

		return self::$_errorCodes['value'];
	}

	static public function TBILLEQ($settlement, $maturity, $discount)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$discount = self::flattenSingleValue($discount);
		$testValue = self::TBILLPRICE($settlement, $maturity, $discount);

		if (is_string($testValue)) {
			return $testValue;
		}

		if (is_string($maturity = self::_getDateValue($maturity))) {
			return self::$_errorCodes['value'];
		}

		if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
			++$maturity;
			$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity) * 360;
		}
		else {
			$daysBetweenSettlementAndMaturity = self::_getDateValue($maturity) - self::_getDateValue($settlement);
		}

		return (365 * $discount) / (360 - ($discount * $daysBetweenSettlementAndMaturity));
	}

	static public function TBILLPRICE($settlement, $maturity, $discount)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$discount = self::flattenSingleValue($discount);

		if (is_string($maturity = self::_getDateValue($maturity))) {
			return self::$_errorCodes['value'];
		}

		if (is_numeric($discount)) {
			if ($discount <= 0) {
				return self::$_errorCodes['num'];
			}

			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				++$maturity;
				$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity) * 360;

				if (!is_numeric($daysBetweenSettlementAndMaturity)) {
					return $daysBetweenSettlementAndMaturity;
				}
			}
			else {
				$daysBetweenSettlementAndMaturity = self::_getDateValue($maturity) - self::_getDateValue($settlement);
			}

			if (360 < $daysBetweenSettlementAndMaturity) {
				return self::$_errorCodes['num'];
			}

			$price = 100 * (1 - (($discount * $daysBetweenSettlementAndMaturity) / 360));

			if ($price <= 0) {
				return self::$_errorCodes['num'];
			}

			return $price;
		}

		return self::$_errorCodes['value'];
	}

	static public function TBILLYIELD($settlement, $maturity, $price)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$price = self::flattenSingleValue($price);

		if (is_numeric($price)) {
			if ($price <= 0) {
				return self::$_errorCodes['num'];
			}

			if (self::$compatibilityMode == self::COMPATIBILITY_OPENOFFICE) {
				++$maturity;
				$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity) * 360;

				if (!is_numeric($daysBetweenSettlementAndMaturity)) {
					return $daysBetweenSettlementAndMaturity;
				}
			}
			else {
				$daysBetweenSettlementAndMaturity = self::_getDateValue($maturity) - self::_getDateValue($settlement);
			}

			if (360 < $daysBetweenSettlementAndMaturity) {
				return self::$_errorCodes['num'];
			}

			return ((100 - $price) / $price) * (360 / $daysBetweenSettlementAndMaturity);
		}

		return self::$_errorCodes['value'];
	}

	static public function SLN($cost, $salvage, $life)
	{
		$cost = self::flattenSingleValue($cost);
		$salvage = self::flattenSingleValue($salvage);
		$life = self::flattenSingleValue($life);
		if (is_numeric($cost) && is_numeric($salvage) && is_numeric($life)) {
			if ($life < 0) {
				return self::$_errorCodes['num'];
			}

			return ($cost - $salvage) / $life;
		}

		return self::$_errorCodes['value'];
	}

	static public function YIELDMAT($settlement, $maturity, $issue, $rate, $price, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$issue = self::flattenSingleValue($issue);
		$rate = self::flattenSingleValue($rate);
		$price = self::flattenSingleValue($price);
		$basis = (int) self::flattenSingleValue($basis);
		if (is_numeric($rate) && is_numeric($price)) {
			if (($rate <= 0) || ($price <= 0)) {
				return self::$_errorCodes['num'];
			}

			$daysPerYear = self::_daysPerYear(self::YEAR($settlement), $basis);

			if (!is_numeric($daysPerYear)) {
				return $daysPerYear;
			}

			$daysBetweenIssueAndSettlement = self::YEARFRAC($issue, $settlement, $basis);

			if (!is_numeric($daysBetweenIssueAndSettlement)) {
				return $daysBetweenIssueAndSettlement;
			}

			$daysBetweenIssueAndSettlement *= $daysPerYear;
			$daysBetweenIssueAndMaturity = self::YEARFRAC($issue, $maturity, $basis);

			if (!is_numeric($daysBetweenIssueAndMaturity)) {
				return $daysBetweenIssueAndMaturity;
			}

			$daysBetweenIssueAndMaturity *= $daysPerYear;
			$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity, $basis);

			if (!is_numeric($daysBetweenSettlementAndMaturity)) {
				return $daysBetweenSettlementAndMaturity;
			}

			$daysBetweenSettlementAndMaturity *= $daysPerYear;
			return (((1 + (($daysBetweenIssueAndMaturity / $daysPerYear) * $rate)) - (($price / 100) + (($daysBetweenIssueAndSettlement / $daysPerYear) * $rate))) / (($price / 100) + (($daysBetweenIssueAndSettlement / $daysPerYear) * $rate))) * ($daysPerYear / $daysBetweenSettlementAndMaturity);
		}

		return self::$_errorCodes['value'];
	}

	static public function YIELDDISC($settlement, $maturity, $price, $redemption, $basis = 0)
	{
		$settlement = self::flattenSingleValue($settlement);
		$maturity = self::flattenSingleValue($maturity);
		$price = self::flattenSingleValue($price);
		$redemption = self::flattenSingleValue($redemption);
		$basis = (int) self::flattenSingleValue($basis);
		if (is_numeric($price) && is_numeric($redemption)) {
			if (($price <= 0) || ($redemption <= 0)) {
				return self::$_errorCodes['num'];
			}

			$daysPerYear = self::_daysPerYear(self::YEAR($settlement), $basis);

			if (!is_numeric($daysPerYear)) {
				return $daysPerYear;
			}

			$daysBetweenSettlementAndMaturity = self::YEARFRAC($settlement, $maturity, $basis);

			if (!is_numeric($daysBetweenSettlementAndMaturity)) {
				return $daysBetweenSettlementAndMaturity;
			}

			$daysBetweenSettlementAndMaturity *= $daysPerYear;
			return (($redemption - $price) / $price) * ($daysPerYear / $daysBetweenSettlementAndMaturity);
		}

		return self::$_errorCodes['value'];
	}

	static public function CELL_ADDRESS($row, $column, $relativity = 1, $referenceStyle = true, $sheetText = '')
	{
		$row = self::flattenSingleValue($row);
		$column = self::flattenSingleValue($column);
		$relativity = self::flattenSingleValue($relativity);
		$sheetText = self::flattenSingleValue($sheetText);
		if (($row < 1) || ($column < 1)) {
			return self::$_errorCodes['value'];
		}

		if ('' < $sheetText) {
			if (strpos($sheetText, ' ') !== false) {
				$sheetText = '\'' . $sheetText . '\'';
			}

			$sheetText .= '!';
		}

		if (!is_bool($referenceStyle) || $referenceStyle) {
			$rowRelative = $columnRelative = '$';
			$column = PHPExcel_Cell::stringFromColumnIndex($column - 1);
			if (($relativity == 2) || ($relativity == 4)) {
				$columnRelative = '';
			}

			if (($relativity == 3) || ($relativity == 4)) {
				$rowRelative = '';
			}

			return $sheetText . $columnRelative . $column . $rowRelative . $row;
		}
		else {
			if (($relativity == 2) || ($relativity == 4)) {
				$column = '[' . $column . ']';
			}

			if (($relativity == 3) || ($relativity == 4)) {
				$row = '[' . $row . ']';
			}

			return $sheetText . 'R' . $row . 'C' . $column;
		}
	}

	static public function COLUMN($cellAddress = NULL)
	{
		if (is_null($cellAddress) || (trim($cellAddress) === '')) {
			return 0;
		}

		if (is_array($cellAddress)) {
			foreach ($cellAddress as $columnKey => $value) {
				$columnKey = preg_replace('/[^a-z]/i', '', $columnKey);
				return (int) PHPExcel_Cell::columnIndexFromString($columnKey);
			}
		}
		else {
			if (strpos($cellAddress, '!') !== false) {
				list($sheet, $cellAddress) = explode('!', $cellAddress);
			}

			if (strpos($cellAddress, ':') !== false) {
				list($startAddress, $endAddress) = explode(':', $cellAddress);
				$startAddress = preg_replace('/[^a-z]/i', '', $startAddress);
				$endAddress = preg_replace('/[^a-z]/i', '', $endAddress);
				$returnValue = array();

				do {
					$returnValue[] = (int) PHPExcel_Cell::columnIndexFromString($startAddress);
				} while ($startAddress++ != $endAddress);

				return $returnValue;
			}
			else {
				$cellAddress = preg_replace('/[^a-z]/i', '', $cellAddress);
				return (int) PHPExcel_Cell::columnIndexFromString($cellAddress);
			}
		}
	}

	static public function COLUMNS($cellAddress = NULL)
	{
		if (is_null($cellAddress) || ($cellAddress === '')) {
			return 1;
		}
		else if (!is_array($cellAddress)) {
			return self::$_errorCodes['value'];
		}

		$x = array_keys($cellAddress);
		$x = array_shift($x);
		$isMatrix = is_numeric($x);
		list($columns, $rows) = PHPExcel_Calculation::_getMatrixDimensions($cellAddress);

		if ($isMatrix) {
			return $rows;
		}
		else {
			return $columns;
		}
	}

	static public function ROW($cellAddress = NULL)
	{
		if (is_null($cellAddress) || (trim($cellAddress) === '')) {
			return 0;
		}

		if (is_array($cellAddress)) {
			foreach ($cellAddress as $columnKey => $rowValue) {
				foreach ($rowValue as $rowKey => $cellValue) {
					return (int) preg_replace('/[^0-9]/i', '', $rowKey);
				}
			}
		}
		else {
			if (strpos($cellAddress, '!') !== false) {
				list($sheet, $cellAddress) = explode('!', $cellAddress);
			}

			if (strpos($cellAddress, ':') !== false) {
				list($startAddress, $endAddress) = explode(':', $cellAddress);
				$startAddress = preg_replace('/[^0-9]/', '', $startAddress);
				$endAddress = preg_replace('/[^0-9]/', '', $endAddress);
				$returnValue = array();

				do {
					$returnValue[][] = (int) $startAddress;
				} while ($startAddress++ != $endAddress);

				return $returnValue;
			}
			else {
				list($cellAddress) = explode(':', $cellAddress);
				return (int) preg_replace('/[^0-9]/', '', $cellAddress);
			}
		}
	}

	static public function ROWS($cellAddress = NULL)
	{
		if (is_null($cellAddress) || ($cellAddress === '')) {
			return 1;
		}
		else if (!is_array($cellAddress)) {
			return self::$_errorCodes['value'];
		}

		$i = array_keys($cellAddress);
		$isMatrix = is_numeric(array_shift($i));
		list($columns, $rows) = PHPExcel_Calculation::_getMatrixDimensions($cellAddress);

		if ($isMatrix) {
			return $columns;
		}
		else {
			return $rows;
		}
	}

	static public function INDIRECT($cellAddress = NULL, PHPExcel_Cell $pCell = NULL)
	{
		$cellAddress = self::flattenSingleValue($cellAddress);
		if (is_null($cellAddress) || ($cellAddress === '')) {
			return self::REF();
		}

		$cellAddress1 = $cellAddress;
		$cellAddress2 = NULL;

		if (strpos($cellAddress, ':') !== false) {
			list($cellAddress1, $cellAddress2) = explode(':', $cellAddress);
		}

		if (!preg_match('/^' . PHPExcel_Calculation::CALCULATION_REGEXP_CELLREF . '$/i', $cellAddress1, $matches) || (!is_null($cellAddress2) && !preg_match('/^' . PHPExcel_Calculation::CALCULATION_REGEXP_CELLREF . '$/i', $cellAddress2, $matches))) {
			return self::REF();
		}

		if (strpos($cellAddress, '!') !== false) {
			list($sheetName, $cellAddress) = explode('!', $cellAddress);
			$pSheet = $pCell->getParent()->getParent()->getSheetByName($sheetName);
		}
		else {
			$pSheet = $pCell->getParent();
		}

		return PHPExcel_Calculation::getInstance()->extractCellRange($cellAddress, $pSheet, false);
	}

	static public function OFFSET($cellAddress = NULL, $rows = 0, $columns = 0, $height = NULL, $width = NULL)
	{
		$rows = self::flattenSingleValue($rows);
		$columns = self::flattenSingleValue($columns);
		$height = self::flattenSingleValue($height);
		$width = self::flattenSingleValue($width);

		if ($cellAddress == NULL) {
			return 0;
		}

		$args = func_get_args();
		$pCell = array_pop($args);

		if (!is_object($pCell)) {
			return self::$_errorCodes['reference'];
		}

		$sheetName = NULL;

		if (strpos($cellAddress, '!')) {
			list($sheetName, $cellAddress) = explode('!', $cellAddress);
		}

		if (strpos($cellAddress, ':')) {
			list($startCell, $endCell) = explode(':', $cellAddress);
		}
		else {
			$startCell = $endCell = $cellAddress;
		}

		list($startCellColumn, $startCellRow) = PHPExcel_Cell::coordinateFromString($startCell);
		list($endCellColumn, $endCellRow) = PHPExcel_Cell::coordinateFromString($endCell);
		$startCellRow += $rows;
		$startCellColumn = PHPExcel_Cell::columnIndexFromString($startCellColumn) - 1;
		$startCellColumn += $columns;
		if (($startCellRow <= 0) || ($startCellColumn < 0)) {
			return self::$_errorCodes['reference'];
		}

		$endCellColumn = PHPExcel_Cell::columnIndexFromString($endCellColumn) - 1;
		if (($width != NULL) && !is_object($width)) {
			$endCellColumn = ($startCellColumn + $width) - 1;
		}
		else {
			$endCellColumn += $columns;
		}

		$startCellColumn = PHPExcel_Cell::stringFromColumnIndex($startCellColumn);
		if (($height != NULL) && !is_object($height)) {
			$endCellRow = ($startCellRow + $height) - 1;
		}
		else {
			$endCellRow += $rows;
		}

		if (($endCellRow <= 0) || ($endCellColumn < 0)) {
			return self::$_errorCodes['reference'];
		}

		$endCellColumn = PHPExcel_Cell::stringFromColumnIndex($endCellColumn);
		$cellAddress = $startCellColumn . $startCellRow;
		if (($startCellColumn != $endCellColumn) || ($startCellRow != $endCellRow)) {
			$cellAddress .= ':' . $endCellColumn . $endCellRow;
		}

		if ($sheetName !== NULL) {
			$pSheet = $pCell->getParent()->getParent()->getSheetByName($sheetName);
		}
		else {
			$pSheet = $pCell->getParent();
		}

		return PHPExcel_Calculation::getInstance()->extractCellRange($cellAddress, $pSheet, false);
	}

	static public function CHOOSE()
	{
		$chooseArgs = func_get_args();
		$chosenEntry = self::flattenArray(array_shift($chooseArgs));
		$entryCount = count($chooseArgs) - 1;

		if (is_array($chosenEntry)) {
			$chosenEntry = array_shift($chosenEntry);
		}

		if (is_numeric($chosenEntry) && !is_bool($chosenEntry)) {
			--$chosenEntry;
		}
		else {
			return self::$_errorCodes['value'];
		}

		$chosenEntry = floor($chosenEntry);
		if (($chosenEntry <= 0) || ($entryCount < $chosenEntry)) {
			return self::$_errorCodes['value'];
		}

		if (is_array($chooseArgs[$chosenEntry])) {
			return self::flattenArray($chooseArgs[$chosenEntry]);
		}
		else {
			return $chooseArgs[$chosenEntry];
		}
	}

	static public function MATCH($lookup_value, $lookup_array, $match_type = 1)
	{
		$lookup_array = self::flattenArray($lookup_array);
		$lookup_value = self::flattenSingleValue($lookup_value);
		$lookup_value = strtolower($lookup_value);
		if (!is_numeric($lookup_value) && !is_string($lookup_value) && !is_bool($lookup_value)) {
			return self::$_errorCodes['na'];
		}

		if (($match_type !== 0) && ($match_type !== -1) && ($match_type !== 1)) {
			return self::$_errorCodes['na'];
		}

		if (sizeof($lookup_array) <= 0) {
			return self::$_errorCodes['na'];
		}

		for ($i = 0; $i < sizeof($lookup_array); ++$i) {
			if (!is_numeric($lookup_array[$i]) && !is_string($lookup_array[$i]) && !is_bool($lookup_array[$i])) {
				return self::$_errorCodes['na'];
			}

			if (is_string($lookup_array[$i])) {
				$lookup_array[$i] = strtolower($lookup_array[$i]);
			}
		}

		if (($match_type == 1) || ($match_type == -1)) {
			$iLastValue = $lookup_array[0];

			for ($i = 0; $i < sizeof($lookup_array); ++$i) {
				if ((($match_type == 1) && ($lookup_array[$i] < $iLastValue)) || (($match_type == -1) && ($iLastValue < $lookup_array[$i]))) {
					return self::$_errorCodes['na'];
				}
			}
		}

		for ($i = 0; $i < sizeof($lookup_array); ++$i) {
			if (($match_type == 0) && ($lookup_array[$i] == $lookup_value)) {
				return $i + 1;
			}

			if (($match_type == -1) && ($lookup_array[$i] < $lookup_value)) {
				if ($i < 1) {
					break;
				}
				else {
					return $i;
				}
			}

			if (($match_type == 1) && ($lookup_value < $lookup_array[$i])) {
				if ($i < 1) {
					break;
				}
				else {
					return $i;
				}
			}
		}

		return self::$_errorCodes['na'];
	}

	static public function INDEX($arrayValues, $rowNum = 0, $columnNum = 0)
	{
		if (($rowNum < 0) || ($columnNum < 0)) {
			return self::$_errorCodes['value'];
		}

		if (!is_array($arrayValues)) {
			return self::$_errorCodes['reference'];
		}

		$rowKeys = array_keys($arrayValues);
		$columnKeys = @array_keys($arrayValues[$rowKeys[0]]);

		if (count($columnKeys) < $columnNum) {
			return self::$_errorCodes['value'];
		}
		else if ($columnNum == 0) {
			if ($rowNum == 0) {
				return $arrayValues;
			}

			$rowNum = $rowKeys[--$rowNum];
			$returnArray = array();

			foreach ($arrayValues as $arrayColumn) {
				if (is_array($arrayColumn)) {
					if (isset($arrayColumn[$rowNum])) {
						$returnArray[] = $arrayColumn[$rowNum];
					}
					else {
						return $arrayValues[$rowNum];
					}
				}
				else {
					return $arrayValues[$rowNum];
				}
			}

			return $returnArray;
		}

		$columnNum = $columnKeys[--$columnNum];

		if (count($rowKeys) < $rowNum) {
			return self::$_errorCodes['value'];
		}
		else if ($rowNum == 0) {
			return $arrayValues[$columnNum];
		}

		$rowNum = $rowKeys[--$rowNum];
		return $arrayValues[$rowNum][$columnNum];
	}

	static public function N($value)
	{
		while (is_array($value)) {
			$value = array_shift($value);
		}

		switch (gettype($value)) {
		case 'double':
		case 'float':
		case 'integer':
			return $value;
			break;

		case 'boolean':
			return (int) $value;
			break;

		case 'string':
			if ((0 < strlen($value)) && ($value[0] == '#')) {
				return $value;
			}

			break;
		}

		return 0;
	}

	static public function TYPE($value)
	{
		$value = self::flattenArrayIndexed($value);
		if (is_array($value) && (1 < count($value))) {
			$a = array_keys($value);
			$a = array_pop($a);

			if (self::isCellValue($a)) {
				return 16;
			}
			else if (self::isMatrixValue($a)) {
				return 64;
			}
		}
		else if (count($value) == 0) {
			return 1;
		}

		$value = self::flattenSingleValue($value);

		switch (gettype($value)) {
		case 'double':
		case 'float':
		case 'integer':
			return 1;
			break;

		case 'boolean':
			return 4;
			break;

		case 'array':
			return 64;
			break;

		case 'string':
			if ((0 < strlen($value)) && ($value[0] == '#')) {
				return 16;
			}

			return 2;
			break;
		}

		return 0;
	}

	static public function SYD($cost, $salvage, $life, $period)
	{
		$cost = self::flattenSingleValue($cost);
		$salvage = self::flattenSingleValue($salvage);
		$life = self::flattenSingleValue($life);
		$period = self::flattenSingleValue($period);
		if (is_numeric($cost) && is_numeric($salvage) && is_numeric($life) && is_numeric($period)) {
			if (($life < 1) || ($life < $period)) {
				return self::$_errorCodes['num'];
			}

			return (($cost - $salvage) * (($life - $period) + 1) * 2) / ($life * ($life + 1));
		}

		return self::$_errorCodes['value'];
	}

	static public function TRANSPOSE($matrixData)
	{
		$returnMatrix = array();

		if (!is_array($matrixData)) {
			$matrixData = array(
				array($matrixData)
				);
		}

		$column = 0;

		foreach ($matrixData as $matrixRow) {
			$row = 0;

			foreach ($matrixRow as $matrixCell) {
				$returnMatrix[$row][$column] = $matrixCell;
				++$row;
			}

			++$column;
		}

		return $returnMatrix;
	}

	static public function MMULT($matrixData1, $matrixData2)
	{
		$matrixAData = $matrixBData = array();

		if (!is_array($matrixData1)) {
			$matrixData1 = array(
				array($matrixData1)
				);
		}

		if (!is_array($matrixData2)) {
			$matrixData2 = array(
				array($matrixData2)
				);
		}

		$rowA = 0;

		foreach ($matrixData1 as $matrixRow) {
			$columnA = 0;

			foreach ($matrixRow as $matrixCell) {
				if (is_string($matrixCell) || ($matrixCell === NULL)) {
					return self::$_errorCodes['value'];
				}

				$matrixAData[$rowA][$columnA] = $matrixCell;
				++$columnA;
			}

			++$rowA;
		}

		try {
			$matrixA = new Matrix($matrixAData);
			$rowB = 0;

			foreach ($matrixData2 as $matrixRow) {
				$columnB = 0;

				foreach ($matrixRow as $matrixCell) {
					if (is_string($matrixCell) || ($matrixCell === NULL)) {
						return self::$_errorCodes['value'];
					}

					$matrixBData[$rowB][$columnB] = $matrixCell;
					++$columnB;
				}

				++$rowB;
			}

			$matrixB = new Matrix($matrixBData);
			if (($rowA != $columnB) || ($rowB != $columnA)) {
				return self::$_errorCodes['value'];
			}

			return $matrixA->times($matrixB)->getArray();
		}
		catch (Exception $ex) {
			return self::$_errorCodes['value'];
		}
	}

	static public function MINVERSE($matrixValues)
	{
		$matrixData = array();

		if (!is_array($matrixValues)) {
			$matrixValues = array(
				array($matrixValues)
				);
		}

		$row = $maxColumn = 0;

		foreach ($matrixValues as $matrixRow) {
			$column = 0;

			foreach ($matrixRow as $matrixCell) {
				if (is_string($matrixCell) || ($matrixCell === NULL)) {
					return self::$_errorCodes['value'];
				}

				$matrixData[$column][$row] = $matrixCell;
				++$column;
			}

			if ($maxColumn < $column) {
				$maxColumn = $column;
			}

			++$row;
		}

		if ($row != $maxColumn) {
			return self::$_errorCodes['value'];
		}

		try {
			$matrix = new Matrix($matrixData);
			return $matrix->inverse()->getArray();
		}
		catch (Exception $ex) {
			return self::$_errorCodes['value'];
		}
	}

	static public function MDETERM($matrixValues)
	{
		$matrixData = array();

		if (!is_array($matrixValues)) {
			$matrixValues = array(
				array($matrixValues)
				);
		}

		$row = $maxColumn = 0;

		foreach ($matrixValues as $matrixRow) {
			$column = 0;

			foreach ($matrixRow as $matrixCell) {
				if (is_string($matrixCell) || ($matrixCell === NULL)) {
					return self::$_errorCodes['value'];
				}

				$matrixData[$column][$row] = $matrixCell;
				++$column;
			}

			if ($maxColumn < $column) {
				$maxColumn = $column;
			}

			++$row;
		}

		if ($row != $maxColumn) {
			return self::$_errorCodes['value'];
		}

		try {
			$matrix = new Matrix($matrixData);
			return $matrix->det();
		}
		catch (Exception $ex) {
			return self::$_errorCodes['value'];
		}
	}

	static public function SUMPRODUCT()
	{
		$arrayList = func_get_args();
		$wrkArray = self::flattenArray(array_shift($arrayList));
		$wrkCellCount = count($wrkArray);

		foreach ($arrayList as $matrixData) {
			$array2 = self::flattenArray($matrixData);
			$count = count($array2);

			if ($wrkCellCount != $count) {
				return self::$_errorCodes['value'];
			}

			foreach ($array2 as $i => $val) {
				if (is_numeric($wrkArray[$i]) && !is_string($wrkArray[$i]) && is_numeric($val) && !is_string($val)) {
					$wrkArray[$i] *= $val;
				}
			}
		}

		return array_sum($wrkArray);
	}

	static public function SUMX2MY2($matrixData1, $matrixData2)
	{
		$array1 = self::flattenArray($matrixData1);
		$array2 = self::flattenArray($matrixData2);
		$count1 = count($array1);
		$count2 = count($array2);

		if ($count1 < $count2) {
			$count = $count1;
		}
		else {
			$count = $count2;
		}

		$result = 0;

		for ($i = 0; $i < $count; ++$i) {
			if (is_numeric($array1[$i]) && !is_string($array1[$i]) && is_numeric($array2[$i]) && !is_string($array2[$i])) {
				$result += ($array1[$i] * $array1[$i]) - ($array2[$i] * $array2[$i]);
			}
		}

		return $result;
	}

	static public function SUMX2PY2($matrixData1, $matrixData2)
	{
		$array1 = self::flattenArray($matrixData1);
		$array2 = self::flattenArray($matrixData2);
		$count1 = count($array1);
		$count2 = count($array2);

		if ($count1 < $count2) {
			$count = $count1;
		}
		else {
			$count = $count2;
		}

		$result = 0;

		for ($i = 0; $i < $count; ++$i) {
			if (is_numeric($array1[$i]) && !is_string($array1[$i]) && is_numeric($array2[$i]) && !is_string($array2[$i])) {
				$result += ($array1[$i] * $array1[$i]) + ($array2[$i] * $array2[$i]);
			}
		}

		return $result;
	}

	static public function SUMXMY2($matrixData1, $matrixData2)
	{
		$array1 = self::flattenArray($matrixData1);
		$array2 = self::flattenArray($matrixData2);
		$count1 = count($array1);
		$count2 = count($array2);

		if ($count1 < $count2) {
			$count = $count1;
		}
		else {
			$count = $count2;
		}

		$result = 0;

		for ($i = 0; $i < $count; ++$i) {
			if (is_numeric($array1[$i]) && !is_string($array1[$i]) && is_numeric($array2[$i]) && !is_string($array2[$i])) {
				$result += ($array1[$i] - $array2[$i]) * ($array1[$i] - $array2[$i]);
			}
		}

		return $result;
	}

	static private function _vlookupSort($a, $b)
	{
		$f = array_keys($a);
		$firstColumn = array_shift($f);

		if (strtolower($a[$firstColumn]) == strtolower($b[$firstColumn])) {
			return 0;
		}

		return strtolower($a[$firstColumn]) < strtolower($b[$firstColumn]) ? -1 : 1;
	}

	static public function VLOOKUP($lookup_value, $lookup_array, $index_number, $not_exact_match = true)
	{
		$lookup_value = self::flattenSingleValue($lookup_value);
		$index_number = self::flattenSingleValue($index_number);
		$not_exact_match = self::flattenSingleValue($not_exact_match);

		if ($index_number < 1) {
			return self::$_errorCodes['value'];
		}

		if (!is_array($lookup_array) || (count($lookup_array) < 1)) {
			return self::$_errorCodes['reference'];
		}
		else {
			$f = array_keys($lookup_array);
			$firstRow = array_pop($f);
			if (!is_array($lookup_array[$firstRow]) || (count($lookup_array[$firstRow]) < $index_number)) {
				return self::$_errorCodes['reference'];
			}
			else {
				$columnKeys = array_keys($lookup_array[$firstRow]);
				$returnColumn = $columnKeys[--$index_number];
				$firstColumn = array_shift($columnKeys);
			}
		}

		if (!$not_exact_match) {
			uasort($lookup_array, array('self', '_vlookupSort'));
		}

		$rowNumber = $rowValue = false;

		foreach ($lookup_array as $rowKey => $rowData) {
			if (strtolower($lookup_value) < strtolower($rowData[$firstColumn])) {
				break;
			}

			$rowNumber = $rowKey;
			$rowValue = $rowData[$firstColumn];
		}

		if ($rowNumber !== false) {
			if (!$not_exact_match && ($rowValue != $lookup_value)) {
				return self::$_errorCodes['na'];
			}
			else {
				return $lookup_array[$rowNumber][$returnColumn];
			}
		}

		return self::$_errorCodes['na'];
	}

	static public function LOOKUP($lookup_value, $lookup_vector, $result_vector = NULL)
	{
		$lookup_value = self::flattenSingleValue($lookup_value);

		if (!is_array($lookup_vector)) {
			return self::$_errorCodes['na'];
		}

		$lookupRows = count($lookup_vector);
		$l = array_keys($lookup_vector);
		$l = array_shift($l);
		$lookupColumns = count($lookup_vector[$l]);
		if ((($lookupRows == 1) && (1 < $lookupColumns)) || (($lookupRows == 2) && ($lookupColumns != 2))) {
			$lookup_vector = self::TRANSPOSE($lookup_vector);
			$lookupRows = count($lookup_vector);
			$l = array_keys($lookup_vector);
			$lookupColumns = count($lookup_vector[array_shift($l)]);
		}

		if (is_null($result_vector)) {
			$result_vector = $lookup_vector;
		}

		$resultRows = count($result_vector);
		$l = array_keys($result_vector);
		$l = array_shift($l);
		$resultColumns = count($result_vector[$l]);
		if ((($resultRows == 1) && (1 < $resultColumns)) || (($resultRows == 2) && ($resultColumns != 2))) {
			$result_vector = self::TRANSPOSE($result_vector);
			$resultRows = count($result_vector);
			$r = array_keys($result_vector);
			$resultColumns = count($result_vector[array_shift($r)]);
		}

		if ($lookupRows == 2) {
			$result_vector = array_pop($lookup_vector);
			$lookup_vector = array_shift($lookup_vector);
		}

		if ($lookupColumns != 2) {
			foreach ($lookup_vector as &$value) {
				if (is_array($value)) {
					$k = array_keys($value);
					$key1 = $key2 = array_shift($k);
					$key2++;
					$dataValue1 = $value[$key1];
				}
				else {
					$key1 = 0;
					$key2 = 1;
					$dataValue1 = $value;
				}

				$dataValue2 = array_shift($result_vector);

				if (is_array($dataValue2)) {
					$dataValue2 = array_shift($dataValue2);
				}

				$value = array($key1 => $dataValue1, $key2 => $dataValue2);
			}

			unset($value);
		}

		return self::VLOOKUP($lookup_value, $lookup_vector, 2);
	}

	static public function flattenArray($array)
	{
		if (!is_array($array)) {
			return (array) $array;
		}

		$arrayValues = array();

		foreach ($array as $value) {
			if (is_array($value)) {
				foreach ($value as $val) {
					if (is_array($val)) {
						foreach ($val as $v) {
							$arrayValues[] = $v;
						}
					}
					else {
						$arrayValues[] = $val;
					}
				}
			}
			else {
				$arrayValues[] = $value;
			}
		}

		return $arrayValues;
	}

	static public function flattenArrayIndexed($array)
	{
		if (!is_array($array)) {
			return (array) $array;
		}

		$arrayValues = array();

		foreach ($array as $k1 => $value) {
			if (is_array($value)) {
				foreach ($value as $k2 => $val) {
					if (is_array($val)) {
						foreach ($val as $k3 => $v) {
							$arrayValues[$k1 . '.' . $k2 . '.' . $k3] = $v;
						}
					}
					else {
						$arrayValues[$k1 . '.' . $k2] = $val;
					}
				}
			}
			else {
				$arrayValues[$k1] = $value;
			}
		}

		return $arrayValues;
	}

	static public function flattenSingleValue($value = '')
	{
		while (is_array($value)) {
			$value = array_pop($value);
		}

		return $value;
	}
}

if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
	PHPExcel_Autoloader::Register();
	PHPExcel_Shared_ZipStreamWrapper::register();

	if (ini_get('mbstring.func_overload') & 2) {
		throw new Exception('Multibyte function overloading in PHP must be disabled for string functions (2).');
	}
}

define('EPS', 2.2200000000000001E-16);
define('MAX_VALUE', 1.1999999999999999E+308);
define('LOG_GAMMA_X_MAX_VALUE', 2.5499999999999999E+305);
define('SQRT2PI', 2.5066282746310007);
define('M_2DIVPI', 0.63661977236758138);
define('XMININ', 2.2300000000000001E-308);
define('MAX_ITERATIONS', 256);
define('FINANCIAL_MAX_ITERATIONS', 128);
define('PRECISION', 8.8800000000000003E-16);
define('FINANCIAL_PRECISION', 1.0E-8);
define('EULER', 2.7182818284590451);
$savedPrecision = ini_get('precision');

if ($savedPrecision < 16) {
	ini_set('precision', 16);
}

require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/JAMA/Matrix.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/trend/trendClass.php';

if (!function_exists('acosh')) {
	function acosh($x)
	{
		return 2 * log(sqrt(($x + 1) / 2) + sqrt(($x - 1) / 2));
	}
}

if (!function_exists('asinh')) {
	function asinh($x)
	{
		return log($x + sqrt(1 + ($x * $x)));
	}
}

if (!function_exists('atanh')) {
	function atanh($x)
	{
		return (log(1 + $x) - log(1 - $x)) / 2;
	}
}

if (!function_exists('money_format')) {
	function money_format($format, $number)
	{
		$regex = array('/%((?:[\\^!\\-]|\\+|\\(|\\=.)*)([0-9]+)?(?:#([0-9]+))?', '(?:\\.([0-9]+))?([in%])/');
		$regex = implode('', $regex);

		if (setlocale(LC_MONETARY, NULL) == '') {
			setlocale(LC_MONETARY, '');
		}

		$locale = localeconv();
		$number = floatval($number);

		if (!preg_match($regex, $format, $fmatch)) {
			trigger_error('No format specified or invalid format', 512);
			return $number;
		}

		$flags = array('fillchar' => preg_match('/\\=(.)/', $fmatch[1], $match) ? $match[1] : ' ', 'nogroup' => 0 < preg_match('/\\^/', $fmatch[1]), 'usesignal' => preg_match('/\\+|\\(/', $fmatch[1], $match) ? $match[0] : '+', 'nosimbol' => 0 < preg_match('/\\!/', $fmatch[1]), 'isleft' => 0 < preg_match('/\\-/', $fmatch[1]));
		$width = (trim($fmatch[2]) ? (int) $fmatch[2] : 0);
		$left = (trim($fmatch[3]) ? (int) $fmatch[3] : 0);
		$right = (trim($fmatch[4]) ? (int) $fmatch[4] : $locale['int_frac_digits']);
		$conversion = $fmatch[5];
		$positive = true;

		if ($number < 0) {
			$positive = false;
			$number *= -1;
		}

		$letter = ($positive ? 'p' : 'n');
		$prefix = $suffix = $cprefix = $csuffix = $signal = '';

		if (!$positive) {
			$signal = $locale['negative_sign'];
			switch (true) {
			case ($locale['n_sign_posn'] == 0) || ($flags['usesignal'] == '('):
				$prefix = '(';
				$suffix = ')';
				break;

			case $locale['n_sign_posn'] == 1:
				$prefix = $signal;
				break;

			case $locale['n_sign_posn'] == 2:
				$suffix = $signal;
				break;

			case $locale['n_sign_posn'] == 3:
				$cprefix = $signal;
				break;

			case $locale['n_sign_posn'] == 4:
				$csuffix = $signal;
				break;
			}
		}

		if (!$flags['nosimbol']) {
			$currency = $cprefix;
			$currency .= ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']);
			$currency .= $csuffix;
			$currency = iconv('ISO-8859-1', 'UTF-8', $currency);
		}
		else {
			$currency = '';
		}

		$space = ($locale[$letter . '_sep_by_space'] ? ' ' : '');
		$number = number_format($number, $right, $locale['mon_decimal_point'], $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
		$number = explode($locale['mon_decimal_point'], $number);
		$n = strlen($prefix) + strlen($currency);
		if ((0 < $left) && ($n < $left)) {
			if ($flags['isleft']) {
				$number[0] .= str_repeat($flags['fillchar'], $left - $n);
			}
			else {
				$number[0] = str_repeat($flags['fillchar'], $left - $n) . $number[0];
			}
		}

		$number = implode($locale['mon_decimal_point'], $number);

		if ($locale[$letter . '_cs_precedes']) {
			$number = $prefix . $currency . $space . $number . $suffix;
		}
		else {
			$number = $prefix . $number . $space . $currency . $suffix;
		}

		if (0 < $width) {
			$number = str_pad($number, $width, $flags['fillchar'], $flags['isleft'] ? STR_PAD_RIGHT : STR_PAD_LEFT);
		}

		$format = str_replace($fmatch[0], $number, $format);
		return $format;
	}
}

if (!function_exists('mb_str_replace') && function_exists('mb_substr') && function_exists('mb_strlen') && function_exists('mb_strpos')) {
	function mb_str_replace($search, $replace, $subject)
	{
		if (is_array($subject)) {
			$ret = array();

			foreach ($subject as $key => $val) {
				$ret[$key] = mb_str_replace($search, $replace, $val);
			}

			return $ret;
		}

		foreach ((array) $search as $key => $s) {
			if ($s == '') {
				continue;
			}

			$r = (!is_array($replace) ? $replace : (array_key_exists($key, $replace) ? $replace[$key] : ''));
			$pos = mb_strpos($subject, $s, 0, 'UTF-8');

			while ($pos !== false) {
				$subject = mb_substr($subject, 0, $pos, 'UTF-8') . $r . mb_substr($subject, $pos + mb_strlen($s, 'UTF-8'), 65535, 'UTF-8');
				$pos = mb_strpos($subject, $s, $pos + mb_strlen($r, 'UTF-8'), 'UTF-8');
			}
		}

		return $subject;
	}
}

?>
