<?php
class SDW_exportCSV {
    /**
     * Exports an array of data to a CSV file.
     *
     * @param array      $data
     * @param array|null $columnNames
     *
     * @return void
     * @throws \App\Helpers\Exception
     */
    public static function createFile(array $data, array $columnNames = null){
        if (empty($data)) {
            throw new Exception('provided array was empty');
        }
        // redirect output to client’s web browser
        header('Content-Type: application/csv'); // can also be text/csv
        header('Content-Disposition: attachment;filename=results-' . time() . '.csv');
        // open the output stream
        $file = fopen('php://output', 'w') or die ('Cannot open output buffer');
        // write column names
        if (null !== $columnNames) {
            fputcsv($file, $columnNames);
        }
        // write data array
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        // release buffer
        fclose($file) or die('Cannot close output buffer or file');
    }
}