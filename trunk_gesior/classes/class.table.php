<?PHP
class Table extends Errors
{
	private $id = '';
	private $name = '';
	private $title = '';
	private $class = '';

	private $rows = array();
	private $styles = array();
	private $scripts = array();

    public function __construct($id = null, $name = null, $title = null, $class = null, $rows = null, $styles = null, $scripts = null)
    {
		if($id != null)
			$this->id = $id;

		if($name != null)
			$this->name = $name;

		if($title != null)
			$this->title = $title;

		if($class != null)
			$this->class = $class;

		if($rows != null)
			$this->rows = $rows;

		if($styles != null)
			$this->styles = $styles;

		if($scripts != null)
			$this->scripts = $scripts;
    }

	public function setId($value)
	{
		$this->id = $value;
	}

	public function setName($value)
	{
		$this->name = $value;
	}

	public function setTitle($value)
	{
		$this->title = $value;
	}

	public function setClass($value)
	{
		$this->class = $value;
	}

	public function setRows($value)
	{
		$this->rows = $value;
	}

	public function setStyles($value)
	{
		$this->styles = $value;
	}

	public function setStyle($key, $value)
	{
		$this->styles[$key] = $value;
	}

	public function setScripts($value)
	{
		$this->scripts = $value;
	}

	public function addRow(TableRow $row)
	{
		$this->rows[] = $row;
	}

	public function getTableString()
	{
		echo '<table>';
		if(count($this->rows) > 0)
			foreach($this->rows as $row)
				echo $row->getRowString();
		echo '</table>';
	}
}

class TableRow extends Errors
{
	private $content = '';
	private $id = '';
	private $name = '';
	private $title = '';
	private $rowspan = 0;
	private $colspan = 0;
	private $class = '';

	private $cells = array();
	private $styles = array();
	private $scripts = array();

    public function __construct($content = null, $id = null, $name = null, $title = null, $rowspan = null, $colspan = null, $class = null, $cells = null, $styles = null, $scripts = null)
    {
		if($content != null)
			$this->content = $content;

		if($id != null)
			$this->id = $id;

		if($name != null)
			$this->name = $name;

		if($title != null)
			$this->title = $title;

		if($rowspan != null)
			$this->rowspan = $rowspan;

		if($colspan != null)
			$this->colspan = $colspan;

		if($class != null)
			$this->class = $class;

		if($cells != null)
			$this->cells = $cells;

		if($styles != null)
			$this->styles = $styles;

		if($scripts != null)
			$this->scripts = $scripts;
    }

	public function getContent()
	{
		return $this->content;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getRowspan()
	{
		return $this->rowspan;
	}

	public function getColspan()
	{
		return $this->colspan;
	}

	public function getClass()
	{
		return $this->class;
	}

	public function getCells()
	{
		return $this->cells;
	}

	public function getStyles()
	{
		return $this->styles;
	}

	public function getStyle($key)
	{
		return $this->styles[$key];
	}

	public function getScripts()
	{
		return $this->scripts;
	}

	public function setContent($value)
	{
		$this->content = $value;
	}

	public function setId($value)
	{
		$this->id = $value;
	}

	public function setName($value)
	{
		$this->name = $value;
	}

	public function setTitle($value)
	{
		$this->title = $value;
	}

	public function setRowspan($value)
	{
		$this->rowspan = $value;
	}

	public function setColspan($value)
	{
		$this->colspan = $value;
	}

	public function setClass($value)
	{
		$this->class = $value;
	}

	public function setCells($value)
	{
		$this->cells = $value;
	}

	public function setStyles($value)
	{
		$this->styles = $value;
	}

	public function setStyle($key, $value)
	{
		$this->styles[$key] = $value;
	}

	public function setScripts($value)
	{
		$this->scripts = $value;
	}

	public function addCell(TableCell $cell)
	{
		$this->cells[] = $cell;
	}

	public function getRowString()
	{
		return '<tr><td>a</td></tr>';
	}
}

class TableCell extends Errors
{
	private $content = '';
	private $id = '';
	private $name = '';
	private $title = '';
	private $rowspan = 0;
	private $colspan = 0;

	private $class = '';
	private $styles = array();
	private $scripts = array();

    public function __construct($content = null, $id = null, $name = null, $title = null, $rowspan = null, $colspan = null, $class = null, $styles = null, $scripts = null)
    {
		if($content != null)
			$this->content = $content;

		if($id != null)
			$this->id = $id;

		if($name != null)
			$this->name = $name;

		if($title != null)
			$this->title = $title;

		if($rowspan != null)
			$this->rowspan = $rowspan;

		if($colspan != null)
			$this->colspan = $colspan;

		if($class != null)
			$this->class = $class;

		if($styles != null)
			$this->styles = $styles;

		if($scripts != null)
			$this->scripts = $scripts;
    }

	public function getContent()
	{
		return $this->content;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getRowspan()
	{
		return $this->rowspan;
	}

	public function getColspan()
	{
		return $this->colspan;
	}

	public function getClass()
	{
		return $this->class;
	}

	public function getStyles()
	{
		return $this->styles;
	}

	public function getStyle($key)
	{
		return $this->styles[$key];
	}

	public function getScripts()
	{
		return $this->scripts;
	}

	public function setContent($value)
	{
		$this->content = $value;
	}

	public function setId($value)
	{
		$this->id = $value;
	}

	public function setName($value)
	{
		$this->name = $value;
	}

	public function setTitle($value)
	{
		$this->title = $value;
	}

	public function setRowspan($value)
	{
		$this->rowspan = $value;
	}

	public function setColspan($value)
	{
		$this->colspan = $value;
	}

	public function setClass($value)
	{
		$this->class = $value;
	}

	public function setStyles($value)
	{
		$this->styles = $value;
	}

	public function setStyle($key, $value)
	{
		$this->styles[$key] = $value;
	}

	public function setScripts($value)
	{
		$this->scripts = $value;
	}

	public function getCellString()
	{
		return '<td>a</td>';
	}
}

?>

