import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;

public class Temperature {

    private File file;
    private FileOutputStream oFile;

    public Temperature() {
        try {
            file = new File("/data/temperatures.txt");
            oFile = new FileOutputStream(file, true);
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        }
    }

    public void Write(String hour, String day, String month, String year, String nr, String temperature) {
        try {
            oFile.write((hour+day+month+year+nr+temperature+"\n").getBytes());
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
