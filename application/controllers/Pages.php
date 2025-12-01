<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{
    public function home()
    {
        $this->load->view('pages/Home');
    }

    public function products()
    {
        $this->load->helper('url');

        $productRoot = FCPATH . 'products';
        $categories = [
            'gpu'   => ['label' => 'GPU', 'base_price' => 450],
            'cpui'  => ['label' => 'CPU', 'base_price' => 320],
            'mobo'  => ['label' => 'Motherboard', 'base_price' => 280],
            'powsu' => ['label' => 'Power Supply', 'base_price' => 150],
            'image' => ['label' => 'GPU', 'base_price' => 400],
        ];
        $descriptionSeeds = [
            'gpu' => [
                'Tuned for high FPS gaming and smooth ray tracing.',
                'Great for 1440p play and creator workloads.',
                'Dual-fan cooling keeps temps stable under load.',
            ],
            'cpui' => [
                'Balanced cores for gaming and streaming together.',
                'Efficient silicon for cooler, quieter builds.',
                'Ready for overclocking with plenty of headroom.',
            ],
            'mobo' => [
                'Robust VRMs and solid PCB for stable power delivery.',
                'Plenty of M.2 slots and headers for clean builds.',
                'Updated BIOS support for the latest chips.',
            ],
            'powsu' => [
                '80+ certified efficiency with quiet operation.',
                'Fully-protected rails for reliable uptime.',
                'Built to handle modern GPUs without coil whine.',
            ],
            'image' => [
                'Built for crisp visuals and buttery frame times.',
                'Compact profile with impressive power efficiency.',
                'Great value pick for esports and indie titles.',
            ],
        ];

        $products = [];
        $id = 1;

        $makeDescription = function ($dir, $prettyName, $id) use ($descriptionSeeds) {
            $pool = isset($descriptionSeeds[$dir]) ? $descriptionSeeds[$dir] : ['Reliable component for your next build.'];
            $phrase = $pool[$id % count($pool)];
            return $prettyName . ' - ' . $phrase;
        };

        foreach ($categories as $dir => $meta) {
            $fullPath = $productRoot . DIRECTORY_SEPARATOR . $dir;
            if (!is_dir($fullPath)) {
                continue;
            }

            $files = array_filter(scandir($fullPath), function ($file) use ($fullPath) {
                return is_file($fullPath . DIRECTORY_SEPARATOR . $file)
                    && preg_match('/\.(png|jpe?g|webp)$/i', $file);
            });

            natsort($files);

            foreach ($files as $file) {
                $name = pathinfo($file, PATHINFO_FILENAME);
                $prettyName = $meta['label'] . ' ' . strtoupper(str_replace(['_', '-'], ' ', $name));
                // Create simple price variety based on id to avoid flat pricing
                $price = $meta['base_price'] + (10 * (($id % 7) + 1));
                $description = $makeDescription($dir, $prettyName, $id);

                $products[] = [
                    'id'       => $id,
                    'name'     => $prettyName,
                    'price'    => $price,
                    'image'    => base_url('products/' . $dir . '/' . $file),
                    'category' => $meta['label'],
                    'description' => $description,
                ];
                $id++;
            }
        }

        $this->load->view('pages/Products', ['products' => $products]);
    }

    public function about()
    {
        $this->load->view('pages/AboutUs');
    }

    public function about_more()
    {
        $this->load->view('pages/AboutUsMore');
    }

    public function tradables()
    {
        $this->load->view('pages/Tradables');
    }

    public function trade_now()
    {
        $this->load->view('pages/TradeNow');
    }

    public function trading()
    {
        $this->load->view('pages/Trading');
    }

    public function ordering()
    {
        $this->load->view('pages/Ordering');
    }

    public function notification()
    {
        $this->load->view('pages/Notification');
    }

    public function order_notification()
    {
        $this->load->view('pages/OrderNotification');
    }

    public function news()
    {
        $this->load->view('pages/News');
    }

    public function contact()
    {
        $this->load->view('pages/Contact');
    }

    public function test()
    {
        $this->load->view('pages/test');
    }
}
