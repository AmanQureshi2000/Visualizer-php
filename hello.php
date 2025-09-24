<?php
// Enhanced Sorting Algorithms Implementation
class SortingAlgorithms {
    private $array = [];
    private $steps = [];
    private $animationSpeed = 1;

    public function __construct($array = [], $animationSpeed = 1) {
        if (!empty($array)) {
            $this->array = $array;
        } else {
            $this->randomizeArray(40);
        }
        $this->animationSpeed = $animationSpeed;
        $this->steps = [['array' => $this->array, 'active' => [], 'sorted' => [], 'pivot' => -1]];
    }

    public function randomizeArray($size = 40) {
        $this->array = [];
        for ($i = 0; $i < $size; $i++) {
            $this->array[] = rand(10, 100);
        }
        $this->steps = [['array' => $this->array, 'active' => [], 'sorted' => [], 'pivot' => -1]];
    }

    public function bubbleSort() {
        $n = count($this->array);
        $this->steps = [['array' => $this->array, 'active' => [], 'sorted' => [], 'pivot' => -1]];
        
        for ($i = 0; $i < $n; $i++) {
            $swapped = false;
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($this->array[$j] > $this->array[$j + 1]) {
                    [$this->array[$j], $this->array[$j + 1]] = [$this->array[$j + 1], $this->array[$j]];
                    $swapped = true;
                }
                $this->steps[] = [
                    'array' => $this->array,
                    'active' => [$j, $j + 1],
                    'sorted' => range($n - $i, $n - 1),
                    'pivot' => -1
                ];
            }
            if (!$swapped) break; // Optimization: stop if no swaps occurred
        }
        return $this->steps;
    }

    public function selectionSort() {
        $n = count($this->array);
        $this->steps = [['array' => $this->array, 'active' => [], 'sorted' => [], 'pivot' => -1]];
        
        for ($i = 0; $i < $n - 1; $i++) {
            $minIdx = $i;
            for ($j = $i + 1; $j < $n; $j++) {
                if ($this->array[$j] < $this->array[$minIdx]) {
                    $minIdx = $j;
                }
                $this->steps[] = [
                    'array' => $this->array,
                    'active' => [$j, $minIdx],
                    'sorted' => range(0, $i - 1),
                    'pivot' => -1
                ];
            }
            if ($minIdx != $i) {
                [$this->array[$i], $this->array[$minIdx]] = [$this->array[$minIdx], $this->array[$i]];
            }
            $this->steps[] = [
                'array' => $this->array,
                'active' => [$i, $minIdx],
                'sorted' => range(0, $i),
                'pivot' => -1
            ];
        }
        return $this->steps;
    }

    public function insertionSort() {
        $n = count($this->array);
        $this->steps = [['array' => $this->array, 'active' => [], 'sorted' => [], 'pivot' => -1]];
        
        for ($i = 1; $i < $n; $i++) {
            $key = $this->array[$i];
            $j = $i - 1;
            while ($j >= 0 && $this->array[$j] > $key) {
                $this->array[$j + 1] = $this->array[$j];
                $this->steps[] = [
                    'array' => $this->array,
                    'active' => [$j + 1, $i],
                    'sorted' => range(0, $i - 1),
                    'pivot' => -1
                ];
                $j--;
            }
            $this->array[$j + 1] = $key;
            $this->steps[] = [
                'array' => $this->array,
                'active' => [$j + 1],
                'sorted' => range(0, $i),
                'pivot' => -1
            ];
        }
        return $this->steps;
    }

    public function quickSort() {
        $this->steps = [['array' => $this->array, 'active' => [], 'sorted' => [], 'pivot' => -1]];
        $this->quickSortHelper(0, count($this->array) - 1);
        return $this->steps;
    }

    private function quickSortHelper($low, $high) {
        if ($low < $high) {
            $pi = $this->partition($low, $high);
            $this->quickSortHelper($low, $pi - 1);
            $this->quickSortHelper($pi + 1, $high);
        }
    }

    private function partition($low, $high) {
        $pivot = $this->array[$high];
        $i = $low - 1;

        for ($j = $low; $j < $high; $j++) {
            if ($this->array[$j] <= $pivot) {
                $i++;
                [$this->array[$i], $this->array[$j]] = [$this->array[$j], $this->array[$i]];
            }
            $this->steps[] = [
                'array' => $this->array,
                'active' => [$j, $i],
                'sorted' => [],
                'pivot' => $high
            ];
        }

        [$this->array[$i + 1], $this->array[$high]] = [$this->array[$high], $this->array[$i + 1]];
        $this->steps[] = [
            'array' => $this->array,
            'active' => [$i + 1, $high],
            'sorted' => [],
            'pivot' => $i + 1
        ];

        return $i + 1;
    }

    public function mergeSort() {
        $this->steps = [['array' => $this->array, 'active' => [], 'sorted' => [], 'pivot' => -1]];
        $this->mergeSortHelper(0, count($this->array) - 1);
        return $this->steps;
    }

    private function mergeSortHelper($left, $right) {
        if ($left < $right) {
            $mid = intval(($left + $right) / 2);
            $this->mergeSortHelper($left, $mid);
            $this->mergeSortHelper($mid + 1, $right);
            $this->merge($left, $mid, $right);
        }
    }

    private function merge($left, $mid, $right) {
        $n1 = $mid - $left + 1;
        $n2 = $right - $mid;

        $L = [];
        $R = [];

        for ($i = 0; $i < $n1; $i++) {
            $L[$i] = $this->array[$left + $i];
        }
        for ($j = 0; $j < $n2; $j++) {
            $R[$j] = $this->array[$mid + 1 + $j];
        }

        $i = 0;
        $j = 0;
        $k = $left;

        while ($i < $n1 && $j < $n2) {
            if ($L[$i] <= $R[$j]) {
                $this->array[$k] = $L[$i];
                $i++;
            } else {
                $this->array[$k] = $R[$j];
                $j++;
            }
            $this->steps[] = [
                'array' => $this->array,
                'active' => [$k],
                'sorted' => [],
                'pivot' => -1
            ];
            $k++;
        }

        while ($i < $n1) {
            $this->array[$k] = $L[$i];
            $this->steps[] = [
                'array' => $this->array,
                'active' => [$k],
                'sorted' => [],
                'pivot' => -1
            ];
            $i++;
            $k++;
        }

        while ($j < $n2) {
            $this->array[$k] = $R[$j];
            $this->steps[] = [
                'array' => $this->array,
                'active' => [$k],
                'sorted' => [],
                'pivot' => -1
            ];
            $j++;
            $k++;
        }
    }

    public function heapSort() {
        $n = count($this->array);
        $this->steps = [['array' => $this->array, 'active' => [], 'sorted' => [], 'pivot' => -1]];

        for ($i = intval($n / 2) - 1; $i >= 0; $i--) {
            $this->heapify($n, $i);
        }

        for ($i = $n - 1; $i > 0; $i--) {
            [$this->array[0], $this->array[$i]] = [$this->array[$i], $this->array[0]];
            $this->steps[] = [
                'array' => $this->array,
                'active' => [0, $i],
                'sorted' => range($i, $n - 1),
                'pivot' => -1
            ];
            $this->heapify($i, 0);
        }
        return $this->steps;
    }

    private function heapify($n, $i) {
        $largest = $i;
        $left = 2 * $i + 1;
        $right = 2 * $i + 2;

        if ($left < $n && $this->array[$left] > $this->array[$largest]) {
            $largest = $left;
        }

        if ($right < $n && $this->array[$right] > $this->array[$largest]) {
            $largest = $right;
        }

        if ($largest != $i) {
            [$this->array[$i], $this->array[$largest]] = [$this->array[$largest], $this->array[$i]];
            $this->steps[] = [
                'array' => $this->array,
                'active' => [$i, $largest],
                'sorted' => [],
                'pivot' => -1
            ];
            $this->heapify($n, $largest);
        }
    }

    public function getArray() {
        return $this->array;
    }

    public function getSteps($algorithm) {
        return match($algorithm) {
            'bubble' => $this->bubbleSort(),
            'selection' => $this->selectionSort(),
            'insertion' => $this->insertionSort(),
            'quick' => $this->quickSort(),
            'merge' => $this->mergeSort(),
            'heap' => $this->heapSort(),
            default => [],
        };
    }
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    $response = [];
    switch ($_POST['action']) {
        case 'randomize':
            $size = isset($_POST['size']) ? intval($_POST['size']) : 40;
            $sorter = new SortingAlgorithms();
            $sorter->randomizeArray($size);
            $response = ['array' => $sorter->getArray()];
            break;
        case 'sort':
            if (isset($_POST['algorithm']) && isset($_POST['array'])) {
                $array = json_decode($_POST['array'], true);
                $algorithm = $_POST['algorithm'];
                $sorter = new SortingAlgorithms($array);
                $response = $sorter->getSteps($algorithm);
            }
            break;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- <title>Enhanced Algorithm Visualizer</title> -->
<style>
:root {
    --primary: #4f8cff;
    --primary-hover: #3570d4;
    --secondary: #ffb347;
    --sorted: #7fff7f;
    --pivot: #ff6b6b;
    --background: #222;
    --container-bg: #2c2f38;
    --bar-bg: #4f8cff;
    --text: #fff;
}

* {
    box-sizing: border-box;
}

body {
    background: var(--background);
    color: var(--text);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 20px;
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    background: var(--container-bg);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    padding: 24px;
    overflow: hidden;
}

h1 {
    text-align: center;
    margin-bottom: 24px;
    letter-spacing: 1px;
    font-weight: 300;
}

.controls {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
    justify-content: center;
    align-items: center;
}

.control-group {
    display: flex;
    gap: 8px;
    align-items: center;
}

select, button, input {
    padding: 10px 16px;
    border-radius: 6px;
    border: none;
    font-size: 1rem;
    background: #3a3e4a;
    color: var(--text);
}

button {
    background: var(--primary);
    color: var(--text);
    cursor: pointer;
    transition: background 0.2s, transform 0.1s;
}

button:hover {
    background: var(--primary-hover);
}

button:active {
    transform: scale(0.98);
}

button:disabled {
    background: #555;
    cursor: not-allowed;
    transform: none;
}

.info-panel {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
    font-size: 0.9rem;
    color: #aaa;
}

.visualizer-container {
    position: relative;
    height: 400px;
    background: #23252b;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 16px;
    border: 1px solid #333;
}

#visualizer {
    display: flex;
    align-items: flex-end;
    height: 100%;
    padding: 0 10px;
}

.bar {
    flex: 1;
    margin: 0 2px;
    background: linear-gradient(180deg, var(--bar-bg) 60%, #1e2a4f 100%);
    border-radius: 4px 4px 0 0;
    transition: height 0.2s, background 0.2s;
}

.bar.active {
    background: linear-gradient(180deg, var(--secondary) 60%, #ff7f50 100%);
}

.bar.sorted {
    background: linear-gradient(180deg, var(--sorted) 60%, #2ecc40 100%);
}

.bar.pivot {
    background: linear-gradient(180deg, var(--pivot) 60%, #cc2c2c 100%);
}

.stats {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
    padding: 15px;
    background: #3a3e4a;
    border-radius: 8px;
}

.stat {
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary);
}

.stat-label {
    font-size: 0.9rem;
    color: #aaa;
}

footer {
    text-align: center;
    color: #888;
    margin-top: 32px;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .controls {
        flex-direction: column;
    }
    
    .control-group {
        width: 100%;
        justify-content: center;
    }
    
    .visualizer-container {
        height: 300px;
    }
    
    .stats {
        flex-direction: column;
        gap: 10px;
    }
}

.legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 4px;
}

.legend-normal {
    background: linear-gradient(180deg, var(--bar-bg) 60%, #1e2a4f 100%);
}

.legend-active {
    background: linear-gradient(180deg, var(--secondary) 60%, #ff7f50 100%);
}

.legend-sorted {
    background: linear-gradient(180deg, var(--sorted) 60%, #2ecc40 100%);
}

.legend-pivot {
    background: linear-gradient(180deg, var(--pivot) 60%, #cc2c2c 100%);
}
</style>
</head>
<body>
<div class="container">
    <h1>Enhanced Algorithm Visualizer</h1>
    
    <div class="legend">
        <div class="legend-item">
            <div class="legend-color legend-normal"></div>
            <span>Normal</span>
        </div>
        <div class="legend-item">
            <div class="legend-color legend-active"></div>
            <span>Active</span>
        </div>
        <div class="legend-item">
            <div class="legend-color legend-sorted"></div>
            <span>Sorted</span>
        </div>
        <div class="legend-item">
            <div class="legend-color legend-pivot"></div>
            <span>Pivot (Quick Sort)</span>
        </div>
    </div>
    
    <div class="controls">
        <div class="control-group">
            <select id="algorithm">
                <option value="bubble">Bubble Sort</option>
                <option value="selection">Selection Sort</option>
                <option value="insertion">Insertion Sort</option>
                <option value="quick">Quick Sort</option>
                <option value="merge">Merge Sort</option>
                <option value="heap">Heap Sort</option>
            </select>
        </div>
        
        <div class="control-group">
            <label for="size">Array Size:</label>
            <input type="number" id="size" min="10" max="100" value="40">
        </div>
        
        <div class="control-group">
            <label for="speed">Speed:</label>
            <input type="range" id="speed" min="1" max="10" value="5">
        </div>
        
        <div class="control-group">
            <button id="randomize">Randomize</button>
            <button id="start">Visualize</button>
            <button id="pause" disabled>Pause</button>
            <button id="reset" disabled>Reset</button>
        </div>
    </div>
    
    <div class="info-panel">
        <div id="algorithm-info">Select an algorithm to see its description</div>
        <div id="step-counter">Step: 0/0</div>
    </div>
    
    <div class="visualizer-container">
        <div id="visualizer"></div>
    </div>
    
    <div class="stats">
        <div class="stat">
            <div class="stat-value" id="comparisons">0</div>
            <div class="stat-label">Comparisons</div>
        </div>
        <div class="stat">
            <div class="stat-value" id="swaps">0</div>
            <div class="stat-label">Swaps</div>
        </div>
        <div class="stat">
            <div class="stat-value" id="time">0ms</div>
            <div class="stat-label">Time</div>
        </div>
    </div>
    
    <footer>&copy; 2024 Enhanced Algorithm Visualizer</footer>
</div>

<script>
const visualizer = document.getElementById('visualizer');
const algorithmSelect = document.getElementById('algorithm');
const sizeInput = document.getElementById('size');
const speedInput = document.getElementById('speed');
const randomizeBtn = document.getElementById('randomize');
const startBtn = document.getElementById('start');
const pauseBtn = document.getElementById('pause');
const resetBtn = document.getElementById('reset');
const stepCounter = document.getElementById('step-counter');
const algorithmInfo = document.getElementById('algorithm-info');
const comparisonsElem = document.getElementById('comparisons');
const swapsElem = document.getElementById('swaps');
const timeElem = document.getElementById('time');

let array = [];
let steps = [];
let currentStep = 0;
let isSorting = false;
let isPaused = false;
let animationId = null;
let startTime = 0;
let comparisons = 0;
let swaps = 0;

// Algorithm descriptions
const algorithmDescriptions = {
    bubble: "Bubble Sort: Repeatedly steps through the list, compares adjacent elements and swaps them if they are in the wrong order.",
    selection: "Selection Sort: Finds the minimum element and places it at the beginning. This process is repeated for the remaining elements.",
    insertion: "Insertion Sort: Builds the final sorted array one item at a time by inserting each element into its correct position.",
    quick: "Quick Sort: Divides the array into smaller arrays around a pivot element, then recursively sorts the sub-arrays.",
    merge: "Merge Sort: Divides the array into halves, sorts each half, then merges them back together in sorted order.",
    heap: "Heap Sort: Converts the array into a max heap, then repeatedly extracts the maximum element to build the sorted array."
};

// Update algorithm info when selection changes
algorithmSelect.addEventListener('change', () => {
    algorithmInfo.textContent = algorithmDescriptions[algorithmSelect.value] || "Select an algorithm to see its description";
});

// Render array with visual effects
function renderArray(active = [], sorted = [], pivot = -1) {
    visualizer.innerHTML = '';
    array.forEach((value, idx) => {
        const bar = document.createElement('div');
        bar.className = 'bar';
        bar.style.height = value * 3.5 + 'px'; // Scale for better visualization
        bar.title = `Value: ${value}, Index: ${idx}`;
        
        if (pivot === idx) bar.classList.add('pivot');
        else if (active.includes(idx)) bar.classList.add('active');
        else if (sorted.includes(idx)) bar.classList.add('sorted');
        
        visualizer.appendChild(bar);
    });
}

// Fetch new random array
async function randomizeArray() {
    if (isSorting) return;
    
    const size = parseInt(sizeInput.value);
    const res = await fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=randomize&size=${size}`
    });
    
    const data = await res.json();
    array = data.array;
    steps = [];
    currentStep = 0;
    comparisons = 0;
    swaps = 0;
    updateStats();
    stepCounter.textContent = 'Step: 0/0';
    renderArray();
    
    // Enable/disable buttons
    startBtn.disabled = false;
    pauseBtn.disabled = true;
    resetBtn.disabled = true;
}

// Start sorting visualization
async function startSort() {
    if (isSorting) return;
    
    isSorting = true;
    isPaused = false;
    startBtn.disabled = true;
    pauseBtn.disabled = false;
    resetBtn.disabled = false;
    
    const algorithm = algorithmSelect.value;
    const res = await fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=sort&algorithm=${algorithm}&array=${JSON.stringify(array)}`
    });
    
    steps = await res.json();
    currentStep = 0;
    comparisons = 0;
    swaps = 0;
    startTime = performance.now();
    
    animateSort();
}

// Animate the sorting process
function animateSort() {
    if (isPaused || currentStep >= steps.length) {
        if (currentStep >= steps.length) {
            // Sorting completed
            isSorting = false;
            startBtn.disabled = false;
            pauseBtn.disabled = true;
            resetBtn.disabled = false;
            
            // Show completion message
            stepCounter.textContent = 'Sorting completed!';
        }
        return;
    }
    
    const step = steps[currentStep];
    array = step.array;
    
    // Count comparisons and swaps
    if (currentStep > 0) {
        const prevStep = steps[currentStep - 1];
        // Simple heuristic for counting comparisons and swaps
        if (JSON.stringify(step.array) !== JSON.stringify(prevStep.array)) {
            swaps++;
        }
        comparisons++;
    }
    
    updateStats();
    stepCounter.textContent = `Step: ${currentStep + 1}/${steps.length}`;
    renderArray(step.active, step.sorted, step.pivot);
    
    currentStep++;
    
    const speed = 110 - (speedInput.value * 10); // Convert 1-10 to 100-10 ms
    animationId = setTimeout(animateSort, speed);
}

// Pause/resume sorting
function togglePause() {
    if (!isSorting) return;
    
    isPaused = !isPaused;
    pauseBtn.textContent = isPaused ? 'Resume' : 'Pause';
    
    if (!isPaused) {
        animateSort();
    } else {
        clearTimeout(animationId);
    }
}

// Reset visualization
function resetVisualization() {
    if (animationId) {
        clearTimeout(animationId);
        animationId = null;
    }
    
    isSorting = false;
    isPaused = false;
    currentStep = 0;
    comparisons = 0;
    swaps = 0;
    
    if (steps.length > 0) {
        array = steps[0].array;
        renderArray();
    }
    
    updateStats();
    stepCounter.textContent = 'Step: 0/0';
    
    startBtn.disabled = false;
    pauseBtn.disabled = true;
    resetBtn.disabled = true;
    pauseBtn.textContent = 'Pause';
}

// Update statistics
function updateStats() {
    comparisonsElem.textContent = comparisons;
    swapsElem.textContent = swaps;
    
    if (startTime > 0) {
        const elapsed = performance.now() - startTime;
        timeElem.textContent = `${Math.round(elapsed)}ms`;
    }
}

// Event listeners
randomizeBtn.addEventListener('click', randomizeArray);
startBtn.addEventListener('click', startSort);
pauseBtn.addEventListener('click', togglePause);
resetBtn.addEventListener('click', resetVisualization);

// Initialize
algorithmInfo.textContent = algorithmDescriptions[algorithmSelect.value];
randomizeArray();
</script>
</body>
</html>