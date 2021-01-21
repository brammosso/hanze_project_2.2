

public class Main {

    public static void main(String[] args) {

        // Create a instance of the DataCollector class with port 7789
        DataCollector dc = new DataCollector(7789);

        // Start listening to incoming messages from clients (loops forever)
        dc.Listen();

    }
}
